from flask import Flask
from flask_cors import CORS, cross_origin
from datetime import datetime
import os

app = Flask(__name__)
CORS(app)

@cross_origin()
@app.route("/takePicture", methods=['POST'])
def takePicture():
    directory = "/Applications/MAMP/htdocs/images/"
    #name = datetime.now().strftime("%d-%m-%Y %H:%M:%S")+".jpg"
    name = "Test.jpg"
    
    stream = os.popen('sudo gphoto2 --capture-image-and-download --filename="'+directory+name+'" --force-overwrite')
    output = stream.read()

    makeThumbNail(directory+name, name)
    return name

def makeThumbNail(path, filename):
    from PIL import Image
    image = Image.open(path)
    THUMB_SIZE = (300, 400)
    image.thumbnail(THUMB_SIZE)
    # creating thumbnail
    image.save('/Applications/MAMP/htdocs/thumbnails/'+filename.replace(".jpg", "")+".jpg")


#import subprocess
#process = subprocess.Popen(['echo', 'More output'],
#                     stdout=subprocess.PIPE, 
#                     stderr=subprocess.PIPE)
#stdout, stderr = process.communicate()
#stdout, stderr
