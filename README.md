![ECOPARK Logo](FrontEnd/ecopark/ecopark.png) 

The system consists of three modules.

**Python Parking System with ANPR**

**ANPR API via Python** (hosted at _ecoparkanpr.40238855.qpc.hal.davecutting.uk_)

**PHP/HTML/JS Front End**


The Python Parking System is to be run on a Raspberry Pi 4 (Ubuntu OS), it runs using a camera plugged into the Pi and is interchangeable for any USB Camera. This makes up the Parking Camera system and is responsible for reading number plates, uploading images of parking and logging the parking against user accounts, it will display their information too once a valid registration has been validated against the API using the ANPR feature.

The system is configured to use two ANPR APIs, a free online based one called PlateRecognizer(https://platerecognizer.com/) which is a derivative of the now deprecated OpenALPR platform. This has been used for testing purposes due to it's high accuracy of 99.9% which made testing much easier.

The system is also configured my own ANPR API via Python which has been discussed above, this is my own take on the ANPR API and uses my own object detection models and OCR tuning to replicate PlateRecognizer's API.

The GUI Application is written using Python and the Tkinter library for GUI programming.

The Front End is run on an Apache server and is responsible for communicating with the API hosted on the EEECS Server. This is written in php, html and JS, it displays all users data to the user and uses the SQL database located on the EEECS SQL Database also.

The APIs are also included in the files under /parkingapi/index.php, /adminapi/index.php and /registerapi/index.php. API-Keys are required to use these for security measures.

**ECOPARK Homepage**
https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/index.php

**ADMIN API Endpoint**
https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/index.php

**PARKING API Endpoint**
https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/index.php

**REGISTRATION API Endpoint**
https://rjohnston80.webhosting3.eeecs.qub.ac.uk/ecopark/index.php

Authored by Ryan Johnston - 40238855
