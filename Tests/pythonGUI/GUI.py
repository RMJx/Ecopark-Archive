from pydoc_data import topics
from tkinter import *
from tkinter import messagebox
from matplotlib.pyplot import close
import requests, json
from functools import partial
import os
from PIL import Image
from PIL import ImageDraw
import cv2
import time
from datetime import datetime
import argparse



w=Tk()
w.geometry('480x320')
w.title('Ecopark Home')
w.resizable(0,0)

from PIL import ImageTk,Image


homeImage =  r"\\wsl.localhost\Ubuntu\home\ryan\Ecopark\Tests\pythonGUI\home.jpg"
verifiedImage = r"\\wsl.localhost\Ubuntu\home\ryan\Ecopark\Tests\pythonGUI\verified.jpg"
imagea=Image.open(homeImage)
imageb= ImageTk.PhotoImage(imagea)

label1 = Label(image=imageb,
               border=0,
               
               justify=CENTER)


label1.place(x=0, y=0)


#Command for button
def cmd():

    imagec=Image.open(verifiedImage)
    imaged = ImageTk.PhotoImage(imagec) 
    if (True):
        messagebox.showinfo("Welcome", "Car registration read successfully, welcome back.")
        q=Toplevel()

        background2 = Label(q, image=imaged,border=0,justify=CENTER)
        background2.place(x=0, y=0)

        q.geometry('480x320')
        q.title('Your details')
        q.resizable(0,0)    

        reg = "X99RMJ"
        make = "Audi"
        colour = "BLACK"
        username = "RJ"
        isParked = "Parked"
        co2Class = '3'
        multiplier  = 1.2
        
        label1 = Label(q, text="Total Due: £5.00" , font=("Helvetica", 17))
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


        parkbttn(112  ,250,"Park",'white','#B266FF', q)
        q.mainloop()
        
    else:
        messagebox.showwarning("No match found.","Error, no car registered on system, please check plate.")


#Button_with hover effect
def bttn(x,y,text,ecolor,lcolor):
    def on_entera(e):
        myButton1['background'] = ecolor #ffcc66
        myButton1['foreground']= lcolor  #000d33

    def on_leavea(e):
        myButton1['background'] = lcolor
        myButton1['foreground']= ecolor

    myButton1 = Button(w,text=text,
                   width=30,
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

#park Button_with hover effect
def parkbttn(x,y,text,ecolor,lcolor, top):
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
                       command=partial(closeWindow, top))
                  
    myButton1.bind("<Enter>", on_entera)
    myButton1.bind("<Leave>", on_leavea)

    myButton1.place(x=x,y=y)

def closeWindow(top):
    top.destroy()

bttn(112  ,250,'Proceed','white','#B266FF')


w.mainloop()

def start_application() -> Application:
    root = Tk()
    app = Application(master=root)
    app.load_settings()
    return app # will return the application without starting the main loop.

if __name__=='__main__':
    start_application().mainloop()