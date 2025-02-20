# Use a Fedora base image
FROM fedora:37

# Set the working directory
WORKDIR /root

# Update the package repository
RUN dnf -y update

# Install required packages
RUN dnf -y install \
    wget \
    libnsl \
    libxcrypt-compat \
    net-tools \
    busybox

# Copy the XAMPP installer from the local machine to the Docker image
COPY xampp-linux-x64-8.2.4-0-installer.run .

# Make the installer executable and install XAMPP
RUN chmod +x xampp-linux-x64-8.2.4-0-installer.run && \
    ./xampp-linux-x64-8.2.4-0-installer.run --mode unattended && \
    rm -f xampp-linux-x64-8.2.4-0-installer.run  # Clean up the installer

# Expose the necessary ports
EXPOSE 80 443

# Start XAMPP services when the container runs
CMD ["/opt/lampp/lampp", "start"]

