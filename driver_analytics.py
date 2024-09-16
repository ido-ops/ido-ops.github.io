import mysql.connector
import json

def get_most_requested_driver():
    try:
        # Database connection
        conn = mysql.connector.connect(
            host="localhost",
            user="your_username",
            password="your_password",
            database="dlcl_transport"
        )
        cursor = conn.cursor(dictionary=True)

        # Execute query
        query = """
        SELECT drivers.driver_id, drivers.name, COUNT(bookings.driver_id) AS num_requests
        FROM bookings
        JOIN drivers ON bookings.driver_id = drivers.driver_id
        GROUP BY bookings.driver_id
        ORDER BY num_requests DESC
        LIMIT 1;
        """
        cursor.execute(query)
        result = cursor.fetchone()

        # Debug: Print result
        print("Query Result:", result)

        return result if result else {"name": None, "num_requests": 0}

    except mysql.connector.Error as err:
        print(f"Error: {err}")
        return None

    finally:
        cursor.close()
        conn.close()

if __name__ == "__main__":
    most_requested_driver = get_most_requested_driver()
    print(json.dumps(most_requested_driver))
