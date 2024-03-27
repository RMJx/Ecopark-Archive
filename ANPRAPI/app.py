from flask import Flask, request
import sys
import torch
import cv2
import numpy
import pytesseract
import os
import re as regex
from itertools import product

def filler(word, from_char, to_char):
    options = [(c,) if c != from_char else (from_char, to_char) for c in word]
    permutations = list(''.join(o) for o in product(*options))
    for x in reversed(permutations):
        if(not formatCheck(x)):
            permutations.remove(x)
    return permutations

def formatCheck(text):
    if(regex.match("(^[A-Z]{2}[0-9]{2}\s?[A-Z]{3}$)|(^[A-Z][0-9]{1,3}[A-Z]{3}$)|(^[A-Z]{3}[0-9]{1,3}[A-Z]$)|(^[0-9]{1,4}[A-Z]{1,2}$)|(^[0-9]{1,3}[A-Z]{1,3}$)|(^[A-Z]{1,2}[0-9]{1,4}$)|(^[A-Z]{1,3}[0-9]{1,3}$)|(^[A-Z]{1,3}[0-9]{1,4}$)|(^[0-9]{3}[DX]{1}[0-9]{3}$)", text)):
        return True
    else:
        return False

def plateRecognition(filePath):
    #get cwd so that we can automatically set yolov5 directory.
    cwd = os.getcwd()
    ##Set our paths so that YOLO and Tesseract can function correctly.
    pytesseract.pytesseract.tesseract_cmd = r'/usr/bin/tesseract'

    #set the path for yolov5 dynamically.
    sys.path.append("/APIANPR/yolov5")


    ##import required libraries from YOLOv5
    from models.experimental import attempt_load
    from utils.general import scale_coords
    from utils.general import non_max_suppression
    from utils.augmentations import letterbox


    #Configure torch
    torchconfig = torch.device('cuda' if torch.cuda.is_available() else 'cpu')

    #Load our weights and map our torchconfig
    weightedModel = attempt_load("/APIANPR/best.pt", map_location=torchconfig)

    ##IMAGE INPUT
    imagePath = cv2.imread(filePath)


    #PREPROCESSING OF IMAGE
    #resizing of the image for uniform results, in this case 640 pixels width using our stride config.
    processedImage = letterbox(imagePath, 640, stride=max(int(weightedModel.stride.max()), 32))[0]

    processedImage = processedImage.transpose((2, 0, 1))[::-1]  #change format from height, width and channel to channel, height width, BGR to RGB colours too to conform with torch.
    processedImage = numpy.ascontiguousarray(processedImage) #image is stored in an unbroken block of memory via numpy.
    processedImage = torch.from_numpy(processedImage).to(torchconfig).float() #numpy array now needs formatted to torch tensor which can then be passed to our torchconfig for the object detection
    processedImage /= 255

    if len(processedImage.shape) == 3:
        processedImage = processedImage[None]  

    #Prediction, use our trained weighted model from Google Colab against the processed image to find the plate.
    #This produces a tensor(tuple) which is a series of the cordinates of the detected plate, the 5th is then the score of confidence of number plate.
    platePrediction = weightedModel(processedImage)[0]
    platePrediction = non_max_suppression(platePrediction, conf_thres=0.5, iou_thres=0.5)

    #print tensor output for logging purposes
    print(platePrediction[0])
    
    ##create array for potential results
    registrations = []

    #grab each cooridnate in the plate prediction tensor object defining the area of the number plate
    for x in platePrediction:
        if len(x):

            x[:, :4] = scale_coords(processedImage.shape[2:], x[:, :4], imagePath.shape).round()
        
            # Convert tensor to numpy formatting which will give us the coordinates of the bounding box for the number plate cropping step.
            numpyFormat = x.cpu().detach().numpy()
            if(numpyFormat is not None):
                for coords in reversed(numpyFormat):
                    #x coords
                    x1,x2 = int(coords[0]), int(coords[2])
                    #y coords
                    y1,y2 = int(coords[1]), int(coords[3])

                    #crop image with the coordinates we now have.
                    tesseractProcessing = imagePath[y1:y2, x1:x2,:]

                    #we apply some image preprocessing via CV2 which allows us to prepare the image better for tesseract.
                    tesseractProcessed = cv2.cvtColor(tesseractProcessing, cv2.COLOR_BGR2GRAY )
                    tesseractProcessed = cv2.bilateralFilter(tesseractProcessed, 10, 15, 15)
                    tesseractProcessed = cv2.resize(tesseractProcessed, None, fx=1.8, fy=1.8)
                    ret,tesseractProcessed = cv2.threshold(tesseractProcessed,120,255,cv2.THRESH_BINARY)
                    ##pass the cropped image into tesseract using the pretrained number plate data supporting GB license plates. psm set accordingly also to read singular line, we specify it to use ONLY uppercase due to GB numberplate formatting, this prevents false results from misidentified characters.
                    registrations.append(pytesseract.image_to_string(tesseractProcessing, config='--psm 10 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', lang='eng').replace('\x0c', '').replace('\n', ''))
                    registrations.append(pytesseract.image_to_string(tesseractProcessing, config='--psm 11 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', lang='eng').replace('\x0c', '').replace('\n', ''))
                    registrations.append(pytesseract.image_to_string(tesseractProcessed, config='--psm 10 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', lang='eng').replace('\x0c', '').replace('\n', ''))
                    registrations.append(pytesseract.image_to_string(tesseractProcessed, config='--psm 11 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', lang='eng').replace('\x0c', '').replace('\n', ''))



    print('\n' + "Results(RAW): " + str(registrations) + '\n')

    ##iterate through registrations, if greater than 7 characters or less than 2 remove item (UK Plates can be 2-7 characters long)
    for x in reversed(registrations):
            if len(x) < 2:
                registrations.remove(x)
            if len(x) > 7:
                registrations.remove(x)

    ##remove duplicates
    registrations = list(dict.fromkeys(registrations))

    ##print unique detections
    print("Pre-processed Results: " + str(registrations))

    ##new list for cleaned legit plates
    results = []

    ##for all permutations replace the I with a 1, O with 0 and vice versa. check they are valid plates then return to new list
    for reg in registrations:

        if('I' in reg):
            possibleRegs = filler(reg, "I", "1")
            for x in possibleRegs:
                if(formatCheck(x)):
                    results.append(x)

        elif('O' in reg):
            possibleRegs = filler(reg, "O", "0")
            for x in possibleRegs:
                if(formatCheck(x)):
                    results.append(x)

        elif('0' in reg):
            possibleRegs = filler(reg, "0", "O")
            for x in possibleRegs:
                if(formatCheck(x)):
                    results.append(x)

        elif('1' in reg):
            possibleRegs = filler(reg, "1", "I")
            for x in possibleRegs:
                if(formatCheck(x)):
                    results.append(x)
        else:
            if(formatCheck(reg)):
                results.append(reg)

    ##remove any further duplicates
    results = list(dict.fromkeys(results))

    ##cleaned plates
    print("")
    for x in results:
        print("Final Results: " + x)
    
    return str(results)



app = Flask(__name__)

@app.route("/", methods=['POST'])

def main():
    file = request.files['image']
    file.save(r'/Users/Ryan/Ecopark/ANPRAPI/image.jpg')
    registrations = plateRecognition(r"/Users/Ryan/Ecopark/ANPRAPI/image.jpg")
    return str(registrations)

    
if __name__ == "__main__":
    app.run()
