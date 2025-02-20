from flask import Flask, send_from_directory

app = Flask(__name__)

@app.route('/')
def home():
    return "Hello, World!"

@app.route('/favicon.ico')
def favicon():
    return send_from_directory('static', 'favicon.ico', mimetype='image/vnd.microsoft.icon')

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000, debug=True)