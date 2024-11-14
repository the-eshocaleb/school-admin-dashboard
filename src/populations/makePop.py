years = [2020, 2021]
programs =['AIs', 'CS', 'DSA', 'ISM', 'SE']
for year in years :
    for program in programs:
        with open(r"C:\wamp64\www\php\semesterTwoProject\src\populations\popTemp.php", "r") as file:
            content = file.read()
            con1 = content.replace("{{year}}", f"{year}")
            con2 = con1.replace("{{program}}", f"{program}")
        with open(f"./src/populations/{program}-{year}.php", "w") as file:
            file.write(con2)
            