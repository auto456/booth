from PIL import Image
import os

for filename in os.listdir("images"):
    if filename.lower().endswith(('.png', '.jpg', '.jpeg', '.tiff', '.bmp', '.gif')):
        filepath = os.path.join("images", filename)
        image = Image.open(filepath)
        THUMB_SIZE = (300, 400)
        image.thumbnail(THUMB_SIZE)
        # creating thumbnail
        image.save('thumbnails/'+filename.replace(".jpg", "")+".jpg")