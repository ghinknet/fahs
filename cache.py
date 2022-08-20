import os, time
import requests

while True:
    # Read donor lists
    for file in os.listdir("donors"):
        print(file)
        try:
            donor_object = requests.get("https://api.foldingathome.org/user/{}".format(file))
            if donor_object.status_code == 200:
                with open(os.path.join("donors", file), "w+") as fb:
                    fb.write(donor_object.text)
        except Exception as e:
            print(e)

    # Read team lists
    for file in os.listdir("teams"):
        print(file)
        try:
            donor_object = requests.get("https://api.foldingathome.org/team/{}".format(file))
            if donor_object.status_code == 200:
                with open(os.path.join("teams", file), "w+") as fb:
                    fb.write(donor_object.text)
        except Exception as e:
            print(e)

    time.sleep(5)
    