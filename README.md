# file-sharing-website
A file sharing website I use to upload and manage screenshots and other files.  
You can find it here: https://fuckmy.cat  
 
<img src="https://letme.fuckmy.cat/OewTl.png">

### Run it on your website
You need to have a working web server with PHP and MySQL or MariaDB.
* Copy all the files from the src/ folder to a virtual host
* Run the database creation script
* Modify fmc.conf, and put it in the home directory of the user running your web server.  
  For Nginx or Apache, the  default user will probably be www-data, and his home folder /var/www  
  I strongly recommend using a different virtual host for the website itself and the uploaded files to prevent executing PHP code in an uploaded file.  
* Give the user running the web server the permission to write in the destination folder  
  (probably ` sudo chown www-data /path/to/uploaded/files `)
* Also modify src/contact.html and the configuration files for ShareX and KShare in src/examples.

### To-do
* Allow users to mark files as "important" so they're deleted after regular files when the user reaches his upload limit
* Show thumbnails instead of the raw uploaded image in the list of uploaded files
* Add nice pictures (Font Awesome?) as thumbnails for other files than images in the list of uploaded files
