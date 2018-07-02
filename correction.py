import os
import shutil
import sys

os.makedirs('tf_files/training_images/' + sys.argv[1])
shutil.move("tf_files/training_images/img.jpg", "tf_files/training_images/" + sys.argv[1] + "/img.jpg")