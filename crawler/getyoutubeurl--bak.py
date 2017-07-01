 #!/usr/bin/env python
# -*- coding: utf-8 -*-
# @Date    : 2016-02-28 20:17:55
# @desc    : 获取youtube 地址分析出视频URL
# @Author  : Raymond Wong (549425036@qq.com)
# @Link    : github/Raymond-Wong

from selenium import webdriver  
from selenium.common.exceptions import NoSuchElementException  
import time  
import re
import subprocess
import requests  
  
def find_sec():  
    url = "https://www.youtube.com/feed/trending"
    pa=re.compile(r'\w+')
    browser = webdriver.Firefox() # Get local session of firefox  
    browser.get(url) # Load page  
    time.sleep(1)    # Let the page load  
    result=[]  
    try: 
        board=browser.find_elements_by_class_name('expanded-shelf-content-item-wrapper')
        max_bindex=len(board)
        for i in range(0,max_bindex):          	 
              board_title=board[i].find_elements_by_tag_name('a')
              video_time=board[i].find_elements_by_class_name('video-time')
               

              board_url_len=len(board_title)
              for j in range(0,board_url_len): 
              	if board_title[j].get_attribute('title') =='' or board_title[j].get_attribute('href').find("watch") == -1:
              	   continue              	   
              	result.append([board_title[j].get_attribute('href'),board_title[j].get_attribute('title')],video_time)

        browser.close()  
        return result  
    except NoSuchElementException:  
        assert 0, "can't find element"  
  
print (find_sec())