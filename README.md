# file-sharing-website
A file sharing website I use to upload and manage screenshots and other files.  
You can find it here: https://fuckmy.cat  
 
<img src="https://letme.fuckmy.cat/OewTl.png">

### Run it on your website
You need to have a working web server with PHP and MySQL or MariaDB.
* Copy all the files from the src/ folder to a virtual host
* Run the database creation script
* Modify fmc.conf, and put it in the home directory of the user running your web server. Don't delete the '/' after paths and URLs.
  For Nginx or Apache, the  default user will probably be www-data, and his home folder /var/www  
  I strongly recommend using a different virtual host for the website itself and the uploaded files to prevent executing PHP code in an uploaded file.  
* Give the user running the web server the permission to write in the destination folder  
  (probably ` sudo chown www-data /path/to/uploaded/files `)
* Also modify src/contact.html and the configuration files for ShareX and KShare in src/examples.

### Some cool features
* Per account upload limit
* Users can mark files as "important" so they're only deleted after regular files when the user reaches the upload limit  
* Files are hashed to detect identical files. A hard link is created if two identical files are uploaded.
* Shows a thumbnails instead of the raw uploaded image in the list of uploaded files if the raw image is too large
* Obviously, it's possible to automatically upload screenshots with ShareX or any other tool that supports custom uploaders

### To-do
* Add nice pictures (Font Awesome?) as thumbnails for other files than images in the list of uploaded files
* Add some filters in the list of uploaded files
