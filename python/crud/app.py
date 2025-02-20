from flask import Flask, render_template, session
from flaskext.mysql import MySQL
import pymysql

mysql = MySQL()
app = Flask(__name__, template_folder='templates')

app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_DB'] = 'crud'
app.config['MYSQL_DATABASE_PASSWORD'] = ''
app.config['MYSQL_DATABASE_HOST'] = 'localhost'

mysql.init_app(app)

@app.route('/')
def index():
    try:
        # Create a connection to the database
        conn = mysql.connect()
        cursor = conn.cursor()
        cursor.execute("SELECT VERSION()")  # Simple query to check connection
        data = cursor.fetchone()
        cursor.close()
        conn.close()
        return f"Database connection successful! Version: {data[0]}"
    except Exception as e:
        return f"Database connection failed: {str(e)}"

if __name__ == '__main__':
    app.run(debug=True) 