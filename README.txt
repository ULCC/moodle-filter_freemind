
FreeMind filter for Moodle 2.x
==============================


This filter scans text for links to files created with the FreeMind
desktop application (such files have an .mm suffix). It replaces
the links with a flash object that displays the .mm file.

The filter recognizes optional parameters appended to the filename.


For a list of supported parameters have a look at the "readme.txt" that 
comes with the freeMindFlashBrowser package. 

The parameters can be given
in any order, but must follow the URL and must be separated by spaces:

    <a href="http://www.example.com/file.php/2/my_novel.mm width=300 height=450 justMap=true">Mindmap</a>

The preceding example will create a mindmap 300 pixels wide by 450 pixels
high and with all option buttons dectivated.


Original download from http://docs.moodle.org/20/en/FreeMind_filter#Installing_the_freemind-filter_package