# Nepali-Date-Picker

### Install:
For npm installation : 
``` bash
$ npm install nepali-date-picker --save
```

For bower installation : 
``` bash
$ bower install nepali-date-picker --save`
```

### How do I use it?
- First include to page css and js files
``` html
<!-- this should go after your </body> -->
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="dist/jquery.nepaliDatePicker.min.js"></script>
<link rel="stylesheet" href="dist/nepaliDatePicker.min.css">
```
- Html
``` html
<input type="text" value="" name="date" class="date-picker">
```
- Javascript
``` javascript
$(".date-picker").nepaliDatePicker({
      dateFormat: "%D, %M %d, %y",
      closeOnDateSelect: true,
      minDate : "सोम, जेठ १०, २०७३",
      maxDate : "मंगल, जेठ ३२, २०७३"
  });
```

## For deplopers only

### Installing dependancies
``` bash
$ bower install

$ npm install

#if you already install grunt dependencies
$ grunt update
```
 
 ### Minifying javascript & css
 ``` bash
 $ grunt compress
 ```
 
 ### Testing calender Functions
  ``` bash
  $ grunt test
  ```
 
 
