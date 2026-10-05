import socket

server = socket.socket()
server.bind(("localhost", 9999))
server.listen(1)

print("Server is waiting for connection...")

conn, addr = server.accept()
print("Client connected")

while True:
    data = conn.recv(1024).decode()

    if data == "exit":
        break

    operator, a, b = data.split(",")
    a = float(a)
    b = float(b)

    if operator == "+":
        result = a + b
    elif operator == "-":
        result = a - b
    elif operator == "*":
        result = a * b
    elif operator == "/":
        if b != 0:
            result = a / b
        else:
            result = "Cannot divide by zero"

    conn.send(str(result).encode())

conn.close()
server.close()