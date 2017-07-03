#!/usr/bin/env python
# -*- coding: utf-8 -*-
# @Date    : 2016-02-28 20:17:55
# @desc    : 获取B站视频地址分析出视频截图
# @Author  : zxr (strive965432@gmail.com)

from selenium import webdriver  
from selenium.common.exceptions import NoSuchElementException  
import time  
import re
import subprocess
import requests
import sys  
  
def find_sec():     
    url = sys.argv[1]  
    browser = webdriver.Firefox() # Get local session of firefox  
    browser.get(url) # Load page  
    time.sleep(1) # Let the page load  
    result=[]  
    try: 
        imgdata = browser.find_elements_by_class_name('cover_image')
        img_len = len(imgdata)
        for i in range(0,img_len):
            img_src = imgdata[i].get_attribute('src')
            r = requests.get(url='http://localhost/php/save.php', params={'href':url,'video_img':img_src}) 
            result.append([img_src])
        browser.close()  
        return result  
    except NoSuchElementException:  
        assert 0, "can't find element"  

print(find_sec())
