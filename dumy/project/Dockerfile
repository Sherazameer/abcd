FROM fedora:latest

# Set the working directory
WORKDIR /usr/src/app

# Install Node.js and npm
RUN dnf -y install nodejs npm

# Copy all files from the current directory into the container's working directory
COPY . /usr/src/app

# Debugging: List the contents of the directory
RUN ls -l /usr/src/app

# Install Angular CLI globally
RUN npm install -g @angular/cli@19

# Install app dependencies (ensure package.json is copied)
RUN npm install --legacy-peer-deps

# Expose the port the app runs on
EXPOSE 4200

# Start the Angular app
CMD ["ng", "serve", "--host", "0.0.0.0"]

