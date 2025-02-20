
import mysql.connector

# Database connection
def create_connection():
    connection = mysql.connector.connect(
        host='localhost',
        user='your_username',  # replace with your MySQL username
        password='your_password',  # replace with your MySQL password
        database='test_db'
    )
    return connection

# Create a new user
def create_user(name, email):
    connection = create_connection()
    cursor = connection.cursor()
    sql = "INSERT INTO users (name, email) VALUES (%s, %s)"
    cursor.execute(sql, (name, email))
    connection.commit()
    cursor.close()
    connection.close()
    print("User  created successfully.")

# Read all users
def read_users():
    connection = create_connection()
    cursor = connection.cursor()
    cursor.execute("SELECT * FROM users")
    users = cursor.fetchall()
    cursor.close()
    connection.close()
    return users

# Update a user
def update_user(user_id, name, email):
    connection = create_connection()
    cursor = connection.cursor()
    sql = "UPDATE users SET name = %s, email = %s WHERE id = %s"
    cursor.execute(sql, (name, email, user_id))
    connection.commit()
    cursor.close()
    connection.close()
    print("User  updated successfully.")

# Delete a user
def delete_user(user_id):
    connection = create_connection()
    cursor = connection.cursor()
    sql = "DELETE FROM users WHERE id = %s"
    cursor.execute(sql, (user_id,))
    connection.commit()
    cursor.close()
    connection.close()
    print("User  deleted successfully.")

# Example usage
if __name__ == "__main__":
    # Create a user
    create_user("John Doe", "john@example.com")

    # Read users
    users = read_users()
    print("Users in the database:")
    for user in users:
        print(user)

    # Update a user (assuming the user ID is 1)
    update_user(1, "Jane Doe", "jane@example.com")

    # Delete a user (assuming the user ID is 1)
    delete_user(1)


#     CREATE DATABASE test_db;

# USE test_db;

# CREATE TABLE users (
#     id INT AUTO_INCREMENT PRIMARY KEY,
#     name VARCHAR(100),
#     email VARCHAR(100)
# );