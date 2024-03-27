from tkinter import *
from tkinter import messagebox
import requests, json
from functools import partial
import os
from PIL import Image
from PIL import ImageDraw
import cv2
import time
from datetime import datetime
import argparse

#get list of available locations.
def locationAPILookup():
    parameters = {'function' : "locations", 'API-KEY' : os.environ['APIKEY'], 'adminusername' : 'admin'}

    r = requests.get("https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/", params=parameters)
    r.encoding='utf-8-sig'
    if(r.text == ""): return "{'location' : ''}"
    else:
        response = json.loads(r.text)
        return response

#specify locational argument
parser = argparse.ArgumentParser()

parser.add_argument('-location', required=True)

args = parser.parse_args()

location = args.location

locations = locationAPILookup()

matchedLocation = False

for x in locations:
    if(location == x['location']):
        matchedLocation = True

if(matchedLocation == False):
    print("Error, no location found for " + location)
    quit()


w=Tk()
w.geometry('480x320')
w.title('Ecopark Home')
w.resizable(0,0)
w.attributes('-zoomed', True)
w.attributes('-fullscreen', True)

from PIL import ImageTk,Image


homeImage =  "/home/ryan/home.jpg"
verifiedImage = "/home/ryan/verified.jpg"
verifiedguestImage = "/home/ryan/verifiedGuest.jpg"
imagea=Image.open(homeImage)
imageb= ImageTk.PhotoImage(imagea)

label1 = Label(image=imageb,
               border=0,
               
               justify=CENTER)


label1.place(x=0, y=0)

def cameraInput():
    cap = cv2.VideoCapture(0)
    cap.set(3,1920)
    cap.set(4,1080)

    if cap.isOpened():
        _,frame = cap.read()
        cap.release()
    if _ and frame is not None:
        UNIX=int(time.time())
        name=datetime.utcfromtimestamp(UNIX).strftime("%Y%m%d%H%M%S")
        watermarkText = datetime.utcfromtimestamp(UNIX).strftime("%Y-%m-%d %H:%M:%S")
        cv2.imwrite(name + ".jpg",frame)
        path=(name+".jpg")
        watermarkImage(path, watermarkText ,position=(0,0))
        return path, UNIX

##car lookup
def parkingAPILookup(reg):
    parameters = {'id' : reg, 'function' : "cardetails", 'API-KEY' : os.environ['APIKEY'], 'username' : 'admin'}

    r = requests.get("https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/", params=parameters)
    r.encoding='utf-8-sig'
    if(r.text is None): return False
    elif(r.text == "No car found"):
        return r.text
    else:
        response = json.loads(r.text)
        return response

##car lookup
def guestparkingAPILookup(reg):
    parameters = {'id' : reg, 'function' : "guestcar", 'API-KEY' : os.environ['APIKEY'], 'username' : 'admin'}

    r = requests.get("https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/", params=parameters)
    r.encoding='utf-8-sig'
    if(r.text is None): return False
    elif(r.text == "No registered guest car found."):
        return r.text
    else:
        response = json.loads(r.text)
        return response

def plateLookup(path, type):
    url = 'http://ecoparkanpr.40238855.qpc.hal.davecutting.uk/'
    files = {'image': open(path, 'rb')}

    response = requests.post(url, files=files)
    resultsList = list(eval(response.text))
    print(resultsList)
    if (type == "user"):
        for x in resultsList:
            lookup = parkingAPILookup(x)
            if(lookup != False and lookup !="No car found."):
                return x
        return "No plate"
    elif(type == "guest"):
        for x in resultsList:
            lookup = guestparkingAPILookup(x)
            if(lookup != False and lookup !="No car found."):
                return x
        return "No plate"


def FTPTransfer(path, numberPlate):
    import ftplib
    imageName = path + numberPlate + ".jpg"
    os.rename(path+".jpg",imageName)
    session = ftplib.FTP('rjohnston80.webhosting3.eeecs.qub.ac.uk','rjohnston80',os.environ['FTPKEY'])
    file = open(imageName,'rb')                  # file to send
    session.storbinary('STOR /httpdocs/ecopark/imagedb/'+imageName, file)     # send the file
    file.close() # close file and FTP
    os.remove(imageName)
    session.quit()

def imageRecognition(type):
    path,timeStamp = cameraInput()
    reg = plateLookup(path, type)

    return reg, timeStamp


def watermarkImage(path_of_image,text,position):
    image = Image.open(path_of_image)
    Draw=ImageDraw.Draw(image)   
    Draw.text(position,text) 
    image.save(path_of_image)

#get list of available locations.
def ratesAPILookup():
    parameters = {'function' : "co2rates", 'API-KEY' : os.environ['APIKEY'], 'adminusername' : 'admin'}

    r = requests.get("https://rjohnston80.webhosting3.eeecs.qub.ac.uk/adminapi/", params=parameters)
    r.encoding='utf-8-sig'
    response = json.loads(r.text)
    return response


#Command for parking button
def cmd():
    #Image Recognition starts, reg and UNIXTime pulled from scan.
    reg, UNIXTimestamp = imageRecognition("user")
    print(reg)
    #response is created and reg passed into parkingAPI call from previous image recognition
    if(reg is not None):
        response = parkingAPILookup(reg)
        #images setup for the page
        imagec=Image.open(verifiedImage)
        imaged = ImageTk.PhotoImage(imagec) 
        if (response != "No car found."):
            messagebox.showinfo("Welcome", "Car registration read successfully, welcome back.")
            q=Toplevel()

            background2 = Label(q, image=imaged,border=0,justify=CENTER)
            background2.place(x=0, y=0)

            q.geometry('480x320')
            q.title('Your details')
            q.resizable(0,0)    
            q.attributes('-fullscreen', True)
            q.attributes('-zoomed', True)

            reg = response['id']
            make = response['make']
            colour = response['colour']
            username = response['userName']
            totalDue = response['totalDue']
            isParked = response ['isParked']
            co2Class = response['co2Class']

            if(isParked==1): 
                isParked = "Parked."
                parkText = "Unpark Car"
            else: 
                isParked = "Not parked."
                parkText = "Park Car"

            rates = ratesAPILookup()
            multiplier = 0
            for x in rates:
                if x['tier'] == int(co2Class):
                    multiplier = x['charge']
        
            label1 = Label(q, text="Total Due: £" + totalDue, font=("Helvetica", 17))
            label2 = Label(q, text="Reg: " + reg.upper(), font=("Helvetica", 17))
            label3 = Label(q, text="Make: " + make, font=("Helvetica", 17))
            label4 = Label(q, text="Colour: " + colour, font=("Helvetica", 17))
            label5 = Label(q, text="Username: " + username, font=("Helvetica", 17))
            label6 = Label(q, text="Status: " + isParked, font=("Helvetica", 17))
            label7 = Label(q, text="CO2 Class: " + co2Class, font=("Helvetica", 17))
            label8 = Label(q, text="CO2 Multiplier: x" + str(multiplier), font=("Helvetica", 17))
        


            label1.place(x=260, y=180)
            label2.place(x=40, y=120)
            label3.place(x=40, y=150)
            label4.place(x=40, y=180)
            label5.place(x=260, y=120)
            label6.place(x=260, y=150)
            label7.place(x=40, y=210)
            label8.place(x=260, y=210)


            parkbttn(112  ,250,parkText,'white','#B266FF', q, reg, UNIXTimestamp)
            q.mainloop()
        
        else:
            messagebox.showwarning("No match found.","Error, no car registered on system, please check plate.")
    else:
        messagebox.showwarning("No reg detected.","Error, no plate detected, please try again and check plate is clean.")

#Command for parking button
def cmd2():
    #Image Recognition starts, reg and UNIXTime pulled from scan.
    reg, UNIXTimestamp = imageRecognition("guest")
    print(reg)
    #response is created and reg passed into parkingAPI call from previous image recognition
    if(reg is not None):
        response = guestparkingAPILookup(reg)
        #images setup for the page
        imagec=Image.open(verifiedguestImage)
        imaged = ImageTk.PhotoImage(imagec) 
        if (response != "No registered guest car found." and response['location'] == location):
            messagebox.showinfo("Welcome", "Car registration read successfully, welcome back.")
            q=Toplevel()

            background2 = Label(q, image=imaged,border=0,justify=CENTER)
            background2.place(x=0, y=0)

            q.geometry('480x320')
            q.title('Your details')
            q.resizable(0,0)    
            q.attributes('-fullscreen', True)
            q.attributes('-zoomed', True)

            reg = response['id']
            hours = response['hours']
            co2Class = response['co2Class']
            username = response['userName']
            isParked = response ['isParked']

            if(isParked==1): 
                isParked = "Parked."
                parkText = "Unpark Car"
            else: 
                isParked = "Not parked."
                parkText = "Park Car"

            rates = ratesAPILookup()
            multiplier = 0
            for x in rates:
                if x['tier'] == int(co2Class):
                    multiplier = x['charge']
        
            label1 = Label(q, text="Reg: " + reg.upper(), font=("Helvetica", 17))
            label3 = Label(q, text="Hours Remaining: " + str(hours), font=("Helvetica", 17))
            label4 = Label(q, text="Username: " + username, font=("Helvetica", 17))
            label5 = Label(q, text="Status: " + isParked, font=("Helvetica", 17))
            label6 = Label(q, text="CO2 Class: " + str(co2Class), font=("Helvetica", 17))
            label7 = Label(q, text="CO2 Multiplier: x" + str(multiplier), font=("Helvetica", 17))
        


            label1.place(x=260, y=190)
            label3.place(x=260, y=160)
            label4.place(x=40, y=190)
            label5.place(x=260, y=130)

            label6.place(x=40, y=160)
            label7.place(x=40, y=130)


            guestparkbttn(112  ,250,parkText,'white','#B266FF', q, reg, UNIXTimestamp, username)
            q.mainloop()
        
        else:
            messagebox.showwarning("No match found.","Error, no car registered on system for this location, please check plate and location.")
    else:
        messagebox.showwarning("No reg detected.","Error, no plate detected, please try again and check plate is clean.")

#Button_with hover effect
def bttn(x,y,text,ecolor,lcolor):
    def on_entera(e):
        myButton1['background'] = ecolor #ffcc66
        myButton1['foreground']= lcolor  #000d33

    def on_leavea(e):
        myButton1['background'] = lcolor
        myButton1['foreground']= ecolor

    myButton1 = Button(w,text=text,
                   width=22,
                   height=3,
                   fg=ecolor,
                   border=0,
                   bg=lcolor,
                   activeforeground=lcolor,
                   activebackground=ecolor,
                       command=cmd)
                  
    myButton1.bind("<Enter>", on_entera)
    myButton1.bind("<Leave>", on_leavea)

    myButton1.place(x=x,y=y)

#Button_with hover effect
def bttn2(x,y,text,ecolor,lcolor):
    def on_entera(e):
        myButton1['background'] = ecolor #ffcc66
        myButton1['foreground']= lcolor  #000d33

    def on_leavea(e):
        myButton1['background'] = lcolor
        myButton1['foreground']= ecolor

    myButton1 = Button(w,text=text,
                   width=22,
                   height=3,
                   fg=ecolor,
                   border=0,
                   bg=lcolor,
                   activeforeground=lcolor,
                   activebackground=ecolor,
                       command=cmd2)
                  
    myButton1.bind("<Enter>", on_entera)
    myButton1.bind("<Leave>", on_leavea)

    myButton1.place(x=x,y=y)

#park Button_with hover effect
def parkbttn(x,y,text,ecolor,lcolor, top, reg, UNIXTimestamp):
    def on_entera(e):
        myButton1['background'] = ecolor #ffcc66
        myButton1['foreground']= lcolor  #000d33

    def on_leavea(e):
        myButton1['background'] = lcolor
        myButton1['foreground']= ecolor

    myButton1 = Button(top,text=text,
                   width=30,
                   height=3,
                   fg=ecolor,
                   border=0,
                   bg=lcolor,
                   activeforeground=lcolor,
                   activebackground=ecolor,
                       command=partial(park, top, reg, UNIXTimestamp))
                  
    myButton1.bind("<Enter>", on_entera)
    myButton1.bind("<Leave>", on_leavea)

    myButton1.place(x=x,y=y)

#park Button_with hover effect
def guestparkbttn(x,y,text,ecolor,lcolor, top, reg, UNIXTimestamp, username):
    def on_entera(e):
        myButton1['background'] = ecolor #ffcc66
        myButton1['foreground']= lcolor  #000d33

    def on_leavea(e):
        myButton1['background'] = lcolor
        myButton1['foreground']= ecolor

    myButton1 = Button(top,text=text,
                   width=30,
                   height=3,
                   fg=ecolor,
                   border=0,
                   bg=lcolor,
                   activeforeground=lcolor,
                   activebackground=ecolor,
                       command=partial(guestpark, top, reg, UNIXTimestamp, username))
                  
    myButton1.bind("<Enter>", on_entera)
    myButton1.bind("<Leave>", on_leavea)

    myButton1.place(x=x,y=y)


##park car
def parkingAPIPut(reg, UNIXTimestamp):
    path = datetime.utcfromtimestamp(UNIXTimestamp).strftime("%Y%m%d%H%M%S")
    FTPTransfer(path, reg)
    parameters = {"id": reg, "time" : UNIXTimestamp , "function" : "park", "location" : location, 'API-KEY' : os.environ['APIKEY'], 'username' : 'admin'}
    r = requests.put('https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/', params=parameters)
    r.encoding='utf-8-sig'
    r = json.loads(r.text)
    return r

##guestpark car
def guestparkingAPIPut(reg, UNIXTimestamp, username):
    path = datetime.utcfromtimestamp(UNIXTimestamp).strftime("%Y%m%d%H%M%S")
    FTPTransfer(path, reg)
    parameters = {"id": reg, "time" : UNIXTimestamp , "function" : "guestpark", "location" : location, 'API-KEY' : os.environ['APIKEY'], 'username' : username}
    r = requests.put('https://rjohnston80.webhosting3.eeecs.qub.ac.uk/parkingapi/', params=parameters)
    r.encoding='utf-8-sig'
    r = json.loads(r.text)
    return r

##park function for button
def park(top, reg, UnixTimestamp):
    parkingAPIPut(reg, UnixTimestamp)
    top.destroy()

##park function for button
def guestpark(top, reg, UnixTimestamp, username):
    guestparkingAPIPut(reg, UnixTimestamp, username)
    top.destroy()


bttn(30  ,250,'User Parking','white','#B266FF')
bttn2(255  ,250,'Guest Parking','white','#B266FF')

w.mainloop()

