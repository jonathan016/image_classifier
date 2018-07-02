import requests
import shutil
import os
import sys
from PIL import Image

response = requests.get(sys.argv[1], stream=True)

if not os.path.exists('tf_files/training_images/'+sys.argv[2]):
        os.makedirs('tf_files/training_images/'+sys.argv[2])

url = sys.argv[1]
reversed = url[::-1]
extension = list()
y = 0
for x in range(0, len(reversed) - 1):
    if reversed[x] != '.':
        extension.append(reversed[x])
    if reversed[x] == '.':
        break
extension = extension[::-1]
strExtension = ''.join(extension)
strExtension = '.' + strExtension

if(strExtension == ".jpg" or strExtension == ".jpeg"):
    with open('tf_files/training_images/'+sys.argv[2]+'/'+sys.argv[3]+strExtension, 'wb') as out_file:
        shutil.copyfileobj(response.raw, out_file)
    del response

    try:
        img = Image.open('tf_files/training_images/'+sys.argv[2]+'/'+sys.argv[3]+strExtension)
        pass
    except Exception:
        os.remove('tf_files/training_images/'+sys.argv[2]+'/'+sys.argv[3]+strExtension)
        sys.exit("Cannot open image")
        pass

    if(sys.argv[2] != "holder"):
        try:
            shutil.move('tf_files/training_images/holder/img'+extension, 'tf_files/training_images/'+sys.argv[2]+'/'+sys.argv[3]+'.jpg')
            pass
        except Exception:
            pass