import mysql.connector
from mysql.connector import Error
import random
import string

def generate_invite_code(length=12):
    characters = string.ascii_letters + string.digits
    return ''.join(random.choice(characters) for _ in range(length))

def add_invite_code(db_config, code, created_by):
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        query = "INSERT INTO invites (invite_code, expires_at, created_by) VALUES (%s, ADDDATE(NOW(), INTERVAL 30 DAY), %s)"
        cursor.execute(query, (code, created_by))
        conn.commit()
        print("Kod zaproszenia został pomyślnie dodany.")
    except Error as e:
        print(f"Błąd: {e}")
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()

db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'bramki'
}

if __name__ == "__main__":
    code = generate_invite_code()
    created_by = 1  # Upewnij się, że ten użytkownik istnieje w tabeli `users`
    add_invite_code(db_config, code, created_by)
