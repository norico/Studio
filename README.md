## Theme and plugin for Wordpress

using tailwindcss outside theme

make DEV dir and install tailwindcss


``` javascript
    "watch" : "npx tailwindcss 
        -i ../wp-content/themes/intranet/style.css 
        -o ../wp-content/themes/intranet/assets/css/style.css 
        --watch"
```

If you are under WSL with your files in Windows, use this script
``` javascript
    "watch" : "npx tailwindcss 
        -i ../wp-content/themes/intranet/style.css 
        -o ../wp-content/themes/intranet/assets/css/style.css 
        --watch --poll"
```