import unittest
import requests
import json


class TestsGUI(unittest.TestCase):

    def testLocationExistsCorrect(self):
        ##mock locations we would receive from API
        locations = [{'location': 'Belfast Central'}, {'location': 'Bangor'}]
        ##mock value entered by admin upon startup of app
        location = "Bangor"
        for x in locations:
            if(location == x['location']):
                matchedLocation = True
            else: 
                matchedLocation = False
        self.assertTrue(matchedLocation)

    def testLocationExistsIncorrect(self):
        ##mock locations we would receive from API
        locations = [{'location': 'Belfast Central'}, {'location': 'Bangor'}]
        ##mock value entered by admin upon startup of app
        location = "Comber"
        for x in locations:
            if(location == x['location']):
                matchedLocation = True
            else: 
                matchedLocation = False
        self.assertFalse(matchedLocation)

    def testgetratesCorrect(self):
        ##mock rates that we would receive from API request
        rates = [{'charge' : 1.2, 'tier' : 0}, {'charge' : 1.1, 'tier' : 1}, {'charge' : 1.0, 'tier' : 2}]
        ##mock co2 class that we would also receive from API request 
        co2Class = 2
        multiplier = 0
        for x in rates:
            if x['tier'] == int(co2Class):
                multiplier = x['charge']

        self.assertEqual(multiplier, 1.0)

    def testgetratesIncorrect(self):
        ##mock rates that we would receive from API request
        rates = [{'charge' : 1.2, 'tier' : 0}, {'charge' : 1.1, 'tier' : 1}, {'charge' : 1.0, 'tier' : 2}]
        ##mock co2 class that we would also receive from API request 
        co2Class = 0
        multiplier = 0
        for x in rates:
            if x['tier'] == int(co2Class):
                multiplier = x['charge']

        self.assertNotEqual(multiplier, 1.0)



if __name__ == '__main__':
    unittest.main()