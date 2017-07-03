# -*- coding: UTF-8 -*-
from selenium import webdriver  
from selenium.common.exceptions import NoSuchElementException  
import time  
import re
import subprocess
import requests  
  
def find_sec():  
    pa=re.compile(r'\w+')  
    browser = webdriver.PhantomJS() # Get local session of firefox  
    browser.get("http://www.bilibili.com/video/ent_funny_1.html") # Load page  
    time.sleep(1) # Let the page load  
    result=[]  
    try: 
        board=browser.find_elements_by_class_name('l-item')
        max_bindex=len(board)
        for i in range(0,max_bindex):  
            board_url=board[i].find_elements_by_class_name('cover-preview')
            board_title=board[i].find_elements_by_tag_name('img')
            board_url_len=len(board_url)
            for j in range(0,board_url_len):
                video_img   = board_title[j].get_attribute('data-img')
                video_title = board_title[j].get_attribute('alt').encode('utf-8')
                video_href  = board_url[j].get_attribute('href')
                result.append([video_title,video_href,video_img])
                r = requests.get(url='http://localhost/php/save.php', params={'title':video_title,'href':video_href,'video_img':video_img})   #å
                args=["phantomjs","/home//getvideourl.js",board_url[j].get_attribute('href')]
                retcode=subprocess.call(args) 
                print(retcode)
                time.sleep(1) # Let the page load  

        browser.close()  
        return result  
    except NoSuchElementException:  
        assert 0, "can't find element"  

print(find_sec())
