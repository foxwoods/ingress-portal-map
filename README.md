Ingress Portal Map (ABANDONED)
==================


程序结构
--------

程序主要分为两个部分：

1. **bookmarklet** 通过一个Bookmarklet向[Intel Map](http://www.ingress.com/intel)页面注入代码，把之后载入的portal数据发到[Firebase](http://www.firebase.com)保存下来。
2. **KML生成程序** 在服务器端运行，从[Firebase](http://www.firebase.com)读取portal数据，修改坐标偏移，输出简单的KML。


使用方法
--------
1. 新建一个Firebase，用来保存portal数据。（[Firebase](http://www.firebase.com)还在内测中，需要先申请Beta Code。我当时在第二天就收到Beta Code了）
2. 修改bookmarklet.min.js，把`https://my-portals.firebaseio.com/`替换为第一步中新建的[Firebase](http://www.firebase.com)地址，然后在最前面加上`javascript:`，把整个文件的内容保存成书签。
3. 在浏览器中打开[Intel Map](http://www.ingress.com/intel)，然后点击上一步中保存的书签，向页面中注入代码。
4. 拖动地图，新加载的portal就会保存到[Firebase](http://www.firebase.com)里了，从Firebase的后台可即时以看到数据。为了保存所有等级的portal，应该把地图放大到"showing all portals"的程度。
5. 修改portal-kml.php，把`https://my-portals.firebaseio.com/`替换为第一步中新建的[Firebase](http://www.firebase.com)地址，把portal-kml.php放到服务器，如果一切正常，通过HTTP访问portal-kml.php，就可以得到KML了。
6. 打开Google Maps并登录，依次选择"My Places" > "Maps" > "CREATE MAP，新建一个地图，通过导入功能导入刚才生成的KML，保存。
7. 好了，你终于看到你的Portal Map了。如果Portal比较多，可能需要翻页才可以看到所有portal。还有这个Portal Map也可以在手机上的Google Maps里看哦！


优点
----
* 没有修改请求参数，不会额外调用Ingress API，保证帐号安全
* 使用Portal Map时不需要登录
* 电脑，手机都可以用
* 修正了中国的地图偏移，可以使用导航功能


ROADMAP
-------
1. ~~实现全自动更新portal数据~~
2. ~~充分利用已经获取的数据，制作可以排序和过滤的portal list页面~~


CREDITS
-------
修正坐标偏移的部分使用了以下项目的数据和算法：
https://github.com/brightman/lbs
