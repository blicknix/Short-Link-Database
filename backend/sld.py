#!/usr/bin/python3
"""Module to find URL in DB. Input is a 3 letter string"""
import sys
import datetime
import mariadb
from secret import db_user, db_password, db_name

def connect_mariadb():
    """Fuction to Connect to MariaDB"""
    try:
        db_connection  = mariadb.connect(
            user=db_user,
            password=db_password,
            host="127.0.0.1",
            port=3306,
            database=db_name
            )
    except mariadb.Error as error_message:
        print(f"Error connecting to MariaDB: {error_message}")
        sys.exit(1)

    return db_connection.cursor()

def get_long_url(short):
    """Fuction to get long URl form short Input"""
    cur = connect_mariadb()
    cur.execute ("Select url, end_date FROM redirects WHERE id=?",(short,))
    tmp = ""
    end_date_var = ""
    for(url,end_date) in cur:
        end_date_var = end_date
        if end_date_var is None:
            tmp = url
        elif end_date_var >= get_date():
            tmp = url
    cur.close()
    return tmp

def get_date():
    """Gibt aktuelles Datum in date Format zurück"""
    return datetime.date.today()

if __name__ == '__main__':
    while True:
        request = sys.stdin.readline().strip()
        response = get_long_url(request)
        f = open("/opt/sld/log.err", "a")
        datum = datetime.datetime.now()
        f.write(datum.strftime("%Y %m %d - %H:%M:%S" ) + " " + request + " " + response + "\n")
        f.close()
        if response == "":
            sys.stdout.write('https://sld.link/index.html\n')
        else:
            sys.stdout.write(response + '\n')
        sys.stdout.flush()
