#!/usr/bin/python

import sys
import Adafruit_DHT

# Parse command line parameters.
sensor = Adafruit_DHT.DHT11
pin = 6
nodeID = 6

humidity, temperature = Adafruit_DHT.read_retry(sensor, pin)

if humidity is not None and temperature is not None:
#    print('Temp={0:0.1f}*  Humidity={1:0.1f}%'.format(temperature, humidity))
    print(str(nodeID) + ',' + str(temperature) + ',' + str(humidity))
    sys.exit(0)
else:
    print('Failed to get reading. Try again!')
    sys.exit(1)
