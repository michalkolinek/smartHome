#!/usr/bin/python

import RPi.GPIO as GPIO
import dht11
import time
import datetime
import sys

# initialize GPIO
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.cleanup()

# read data using pin 14
pin = 6
nodeID = 6
instance = dht11.DHT11(pin)

while True:
    result = instance.read()
    if result.is_valid():
	break
    time.sleep(1)

print(str(nodeID) + ',' + str(result.temperature) + ',' + str(result.humidity))
sys.exit(0);