import sys
import smtplib
from email.message import EmailMessage
import mysql.connector

# Get trainer ID, username, and password from PHP
trainer_id = sys.argv[1]
username = sys.argv[2]
password = sys.argv[3]

# Connect to MySQL
try:
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # Your MySQL root password if any
        database="unispa"
    )

    cursor = conn.cursor()
    cursor.execute("SELECT trainer_name, email FROM trainer WHERE trainer_id = %s", (trainer_id,))

    result = cursor.fetchone()

    if not result:
        print("Error: Trainer not found.")
        exit()

    name, to_email = result
    cursor.close()
    conn.close()

except mysql.connector.Error as err:
    print("Database error:", err)
    exit()

# Your Gmail credentials
sender_email = "2025197779@student.uitm.edu.my"
app_password = "jgjyyknryvkaycyi"  # remove spaces!

# Create email
msg = EmailMessage()
msg['Subject'] = "Your UniSpa Trainer Account"
msg['From'] = sender_email
msg['To'] = to_email
msg.set_content(f"""
Hello {name},

Welcome to UniSpa!

Your trainer account has been created. Please use the following credentials to log in:

Username: {username}
Password: {password}


Login here: http://localhost/UNISPA/trainer-login.php

Regards,  
UniSpa Admin
""")

# Send email
try:
    with smtplib.SMTP_SSL('smtp.gmail.com', 465) as smtp:
        smtp.login(sender_email, app_password)
        smtp.send_message(msg)
    print("Success: Email sent to", to_email)
except Exception as e:
    print("Error:", e)
