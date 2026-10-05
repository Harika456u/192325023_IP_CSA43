import socket

client = socket.socket()
client.connect(("localhost", 9999))

print("Connected to Server")

while True:
    print("\n1. Addition")
    print("2. Subtraction")
    print("3. Multiplication")
    print("4. Division")
    print("5. Exit")

    choice = input("Enter your choice: ")

    if choice == "5":
        client.send("exit".encode())
        break

    a = input("Enter first number: ")
    b = input("Enter second number: ")

    if choice == "1":
        operator = "+"
    elif choice == "2":
        operator = "-"
    elif choice == "3":
        operator = "*"
    elif choice == "4":
        operator = "/"
    else:
        print("Invalid choice")
        continue

    message = operator + "," + a + "," + b
    client.send(message.encode())

    result = client.recv(1024).decode()
    print("Result:", result)

client.close()