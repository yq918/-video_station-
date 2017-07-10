#!/usr/bin/env python
# -*- coding: utf-8 -*-
# @Date    : 2017-07-03 20:17:55
# @desc    : 获取youtube 音乐地址分析出视频URL
# @Author  : zxr (strive965432@gmail.com)


from selenium import webdriver  
from selenium.common.exceptions import NoSuchElementException  
import time  
import re
import subprocess
import requests  
  
def find_sec():  
    url = "https://www.youtube.com/channel/UC-9-kyTW8ZkZNDHQJ6FgpwQ"
    pa=re.compile(r'\w+')
    browser = webdriver.Firefox() # Get local session of firefox  
    browser.get(url) # Load page  
    time.sleep(1)    # Let the page load  
    result=[]
    total = 0  
    try: 
        board=browser.find_elements_by_class_name('yt-uix-shelfslider-list')  
        max_bindex=len(board)
        for i in range(0,max_bindex):
              linkInfo    = board[i].find_elements_by_class_name('yt-lockup-title')
              lockContent = board[i].find_elements_by_class_name('yt-lockup-content')
              thumbData   = board[i].find_elements_by_class_name('yt-thumb-clip')  
              linkLen  = len(linkInfo)
              for j in range(0,linkLen):                                       
                  info   =  linkInfo[j]
                  href  =  info.find_elements_by_class_name('yt-uix-sessionlink')[0].get_attribute('href')
                  title =  info.find_elements_by_class_name('yt-uix-sessionlink')[0].get_attribute('title')
                  if title =='' or href.find("watch") == -1:
                     continue                
                  play_time = info.find_elements_by_class_name('accessible-description')[0].get_attribute("innerHTML") #播放时间               
                  playback  = lockContent[j].find_elements_by_class_name('yt-lockup-meta-info')
                  playbackInfo = playback[0]
                  playback_times = playbackInfo.text    #播放次数                   
                  imgData = thumbData[j].find_elements_by_tag_name('img')
                  imgaddress = imgData[0].get_attribute('data-thumb')
                  requests.get(url='localhost/console/youtube/savedata.php', params={'href':href,'title':title,'play_time':play_time,'playback_times':playback_times,'imgaddress':imgaddress,'cat_id':3}) 
                  result.append([title,href,play_time,playback_times,imgaddress])
                  total++;
                  if total == 31: #单次最多写30条记录
                     break 
        browser.close()  
        return result  
    except NoSuchElementException:  
        assert 0, "can't find element"  
  
print (find_sec())
