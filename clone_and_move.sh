#!/bin/bash

# Clone the repository into a temporary directory
git clone https://github.com/bhavi-creations/nivis_lab.git /tmp/temporary_directory

# Check if the clone was successful
if [ $? -ne 0 ]; then
    echo "Git clone failed. Exiting."
    exit 1
fi

# Ensure the target directory exists
mkdir -p /home/nivislabs/public_html/

# Use rsync to add new files and update existing files
rsync -av --update /tmp/temporary_directory/ /home/nivislabs/public_html/

# Clean up - delete the temporary directory
rm -rf /tmp/temporary_directory

# Provide execution permission to the script itself (if needed in future)
chmod +x "$0"

echo "Files successfully added or updated."
