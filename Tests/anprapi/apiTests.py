import unittest
from itertools import product
import re as regex
import pytesseract
import cv2

class TestsProxy(unittest.TestCase):

    def testFormatCheckCorrect(self):

        def formatCheck(text):
            if(regex.match("(^[A-Z]{2}[0-9]{2}\s?[A-Z]{3}$)|(^[A-Z][0-9]{1,3}[A-Z]{3}$)|(^[A-Z]{3}[0-9]{1,3}[A-Z]$)|(^[0-9]{1,4}[A-Z]{1,2}$)|(^[0-9]{1,3}[A-Z]{1,3}$)|(^[A-Z]{1,2}[0-9]{1,4}$)|(^[A-Z]{1,3}[0-9]{1,3}$)|(^[A-Z]{1,3}[0-9]{1,4}$)|(^[0-9]{3}[DX]{1}[0-9]{3}$)", text)):
                return True
            else:
                return False

        ##list all valid number plate variations

        validPlates = ["AB51ABC", "A123ABC", "A12ABC", "A1ABC", "ABC123A", "ABC1A", "ABC12A", "1ABC", "AB1234", "1234AB", "ABC123", "ABC1234", "101D234"]

        for reg in validPlates:
            ##Checks all plates in the validPlates object verify with the regex provided.
            self.assertTrue(formatCheck(reg))

    def testFormatCheckIncorrect(self):

        def formatCheck(text):
            if(regex.match("(^[A-Z]{2}[0-9]{2}\s?[A-Z]{3}$)|(^[A-Z][0-9]{1,3}[A-Z]{3}$)|(^[A-Z]{3}[0-9]{1,3}[A-Z]$)|(^[0-9]{1,4}[A-Z]{1,2}$)|(^[0-9]{1,3}[A-Z]{1,3}$)|(^[A-Z]{1,2}[0-9]{1,4}$)|(^[A-Z]{1,3}[0-9]{1,3}$)|(^[A-Z]{1,3}[0-9]{1,4}$)|(^[0-9]{3}[DX]{1}[0-9]{3}$)", text)):
                return True
            else:
                return False

        ##list invalid number plate variations

        invalidPlates = ["ABC54ABC", "AB54AB", "AB1ABC", "AABC", "A1ABCD", "ABCD123A", "ABC1AB", "ABCD1A", "ABC", "12345A", "A12345", "1ABCD", "12"]

        for reg in invalidPlates:
            ##Checks all plates in the invalidPlates object do not verify with the regex provided.
            self.assertFalse(formatCheck(reg))


    def testReplacementfunctionCorrect(self):

        def formatCheck(text):
            if(regex.match("(^[A-Z]{2}[0-9]{2}\s?[A-Z]{3}$)|(^[A-Z][0-9]{1,3}[A-Z]{3}$)|(^[A-Z]{3}[0-9]{1,3}[A-Z]$)|(^[0-9]{1,4}[A-Z]{1,2}$)|(^[0-9]{1,3}[A-Z]{1,3}$)|(^[A-Z]{1,2}[0-9]{1,4}$)|(^[A-Z]{1,3}[0-9]{1,3}$)|(^[A-Z]{1,3}[0-9]{1,4}$)|(^[0-9]{3}[DX]{1}[0-9]{3}$)", text)):
                return True
            else:
                return False

        def filler(word, from_char, to_char):
            options = [(c,) if c != from_char else (from_char, to_char) for c in word]
            permutations = list(''.join(o) for o in product(*options))
            for x in reversed(permutations):
                if(not formatCheck(x)):
                    permutations.remove(x)
            return permutations

        ##list some misread number plate variations

        misreadPlates = ["IJ1OLAO", "SJO2ABC", "X9ORMJ", "M7OOKUS"]
        correctPlates = ["IJ10LAO", "SJ02ABC", "X90RMJ", "M700KUS"]

        for reg in misreadPlates:
            fixed = list(filler(reg, 'O', '0'))
            for reg2 in correctPlates:
                for reg3 in fixed:
                    if (reg2 == reg3):
                        print("\n Correct Reg: " + reg3)
                        self.assertEqual(reg2, reg3)

    def testReplacementfunctionIncorrect(self):

        def formatCheck(text):
            if(regex.match("(^[A-Z]{2}[0-9]{2}\s?[A-Z]{3}$)|(^[A-Z][0-9]{1,3}[A-Z]{3}$)|(^[A-Z]{3}[0-9]{1,3}[A-Z]$)|(^[0-9]{1,4}[A-Z]{1,2}$)|(^[0-9]{1,3}[A-Z]{1,3}$)|(^[A-Z]{1,2}[0-9]{1,4}$)|(^[A-Z]{1,3}[0-9]{1,3}$)|(^[A-Z]{1,3}[0-9]{1,4}$)|(^[0-9]{3}[DX]{1}[0-9]{3}$)", text)):
                return True
            else:
                return False

        def filler(word, from_char, to_char):
            options = [(c,) if c != from_char else (from_char, to_char) for c in word]
            permutations = list(''.join(o) for o in product(*options))
            for x in reversed(permutations):
                if(not formatCheck(x)):
                    permutations.remove(x)
            return permutations

        ##list some misread number plate variations

        misreadPlates = ["IJ1OLAO", "SJO2ABC", "X9ORMJ", "M7OOKUS"]

        for reg in misreadPlates:
            fixed = list(filler(reg, 'O', '0'))
            for reg3 in fixed:
                    print("\n Incorrect Reg: " + reg)
                    self.assertNotEqual(reg, reg3)

    def testCharlengthCorrect(self):
        ##ensures plate is less than 7 chars but greater than 1 and also confines to the reg format.

        #test reg data from OCR, only 3/4 regs are valid.

        registrations = ["TE57LOL", "WR0NGPlate", "TE55LAA", "RJ40LOL"]

        ##remove any wrong plates
        for x in reversed(registrations):
            if len(x) < 2:
                registrations.remove(x)
            if len(x) > 7:
                registrations.remove(x)
        self.assertEqual(registrations, ["TE57LOL", "TE55LAA", "RJ40LOL"])

    def testCharlengthIncorrect(self):
        ##ensures plate is less than 7 chars but greater than 1 and also confines to the reg format.

        #test reg data from OCR, only 3/4 regs are valid.

        registrations = ["TE57LOL", "WR0NGPlate", "TE55LAA", "RJ40LOL"]

        ##remove any wrong plates
        for x in reversed(registrations):
            if len(x) < 2:
                registrations.remove(x)
            if len(x) > 7:
                registrations.remove(x)
        self.assertNotEqual(registrations, ["TE57LOL", "WR0NGPlate", "TE55LAA", "RJ40LOL"])

    def testduplicatecheckCorrect(self):
        ##ensures plate is less than 7 chars but greater than 1 and also confines to the reg format.

        #test reg data from OCR, only 3/4 regs are valid.

        registrations = ["TE57LOL", "TE57LOL", "TE55LAA", "TE55LAA"]

        ##remove any duplicate plates
        registrations = list(dict.fromkeys(registrations))
        self.assertEqual(registrations, ["TE57LOL", "TE55LAA"])

    def testduplicatecheckInorrect(self):
        ##ensures plate is less than 7 chars but greater than 1 and also confines to the reg format.

        #test reg data from OCR, only 3/4 regs are valid.

        registrations = ["TE57LOL", "TE57LOL", "TE55LAA", "TE55LAA"]

        ##remove any duplicate plates
        registrations = list(dict.fromkeys(registrations))
        self.assertNotEqual(registrations, ["TE57LOL", "TE57LOL", "TE55LAA", "TE55LAA"])

    def testTesseractOCRCorrect(self):
        ##dummy test on cropped plate.
        pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"
        ##image contains plate X99RMJ
        testImg = cv2.imread(r"\\wsl.localhost\Ubuntu\home\ryan\Ecopark\Tests\anprapi\testPlate.jpg")
        result = pytesseract.image_to_string(testImg, config='--psm 10 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', lang='eng2').replace('\n', '')

        self.assertEqual(result, "X99RMJ")

    def testTesseractOCRIncorrect(self):
        ##dummy test on cropped plate.
        pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"
        ##image contains plate X99RMJ
        testImg = cv2.imread(r"\\wsl.localhost\Ubuntu\home\ryan\Ecopark\Tests\anprapi\testPlate.jpg")
        result = pytesseract.image_to_string(testImg, config='--psm 10 -c tessedit_char_whitelist=ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', lang='eng2').replace('\n', '')

        self.assertNotEqual(result, "X9RMJ")


if __name__ == '__main__':
    unittest.main()
