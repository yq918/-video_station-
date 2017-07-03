#!/usr/bin/env python
# -*- coding: utf-8 -*-
# @Date    : 2016-02-28 20:17:55
# @desc    : 获取youtube 地址分析出视频URL
# @Author  : zxr (strive965432@gmail.com)
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
    browser = webdriver.PhantomJS() # Get local session of firefox  
    browser.get(url) # Load page  
    time.sleep(1)    # Let the page load  
    result=[]  
    try: 
        board=browser.find_elements_by_class_name('expanded-shelf-content-item-wrapper')
        max_bindex=len(board)
        for i in range(0,max_bindex):          	 
              board_title=board[i].find_elements_by_tag_name('a')
              #播放时长 start 
              video_time_tag=board[i].find_elements_by_class_name('video-time')
              video_time_len = len(video_time_tag)
              video_time_txt='' 
              for k in range(0,video_time_len):
                   video_time_txt = video_time_tag[k].text.encode('utf-8')
              #播放时长 end 
              
              #更新时间与播放次数 start
              update_time_tag = board[i].find_elements_by_class_name('yt-lockup-meta-info') 
              update_time_txt = update_time_tag[0].text.encode('utf-8')
              #更新时间与播放次数 end
              
              #查找图片地址 start              
              imglist = board[i].find_elements_by_tag_name('img')
              imglen  = len(imglist)
              for key in range(0,imglen):                   
                   if imglist[key].get_attribute('src').find('?sqp') == -1:
                      continue
                   img_src = imglist[key].get_attribute('src')
              #查找图片地址 end 
                       
              board_url_len=len(board_title)
              for j in range(0,board_url_len):
                video_title =  board_title[j].get_attribute('title')
                video_href  =  board_title[j].get_attribute('href')
                if video_title =='' or video_href.find("watch") == -1:
              	   continue
                requests.get(url='http://colsole.13520v.com/crawler/youtube/savedata.php', params={'video_url':video_href,'video_title':video_title,'play_duration':video_time_txt,'video_time_content':update_time_txt,'video_cover':img_src}) 
                result.append([board_title[j].get_attribute('href'),board_title[j].get_attribute('title').encode('utf-8'),video_time_txt,update_time_txt,img_src])

        browser.close()  
        return result  
    except NoSuchElementException:  
        assert 0, "can't find element"  
  
print (find_sec())
