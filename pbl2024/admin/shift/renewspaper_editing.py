#!/usr/bin/env python
# coding: utf-8

# In[1]:


from pulp import *


# In[2]:


import pulp


# In[3]:


import pandas as pd


# ## パラメーター

# ### 職員

# In[4]:


# M: 職員の集合

M = ['局次長・部長A', 
'部長A', '部長B', '部長C','部長D', '部長E', 
'副部長A', '副部長B', '副部長C', '副部長D', '副部長E', '副部長F', '副部長G', '副部長H', '副部長I', '副部長J', '副部長K', 
'部員A', '部員B', '部員C', '部員D', '部員E', '部員F', '部員G', '部員H', '部員I', '部員J', '部員K', '部員L', '部員M', '部員N', '部員O', '部員P', '部員Q', '部員R']


# ### 指定した月の日付のリスト

# In[5]:


# 年と月を指定
import argparse
import sys

year = int(sys.argv[2])
month = int(sys.argv[3])


# In[6]:


import calendar

# 指定した月のカレンダーを作成し、月の最終日を得る
last_day = calendar.monthrange(year, month)[1]
last_day_as_int = int(last_day)  # 最終日を整数で取得

D = list(range(1, last_day_as_int + 1 ))


# ### 営業日のリスト

# In[7]:


import jpbizday
import datetime
import calendar

def get_business_days(year, month):
    business_days = []
    # 月の最初の日を取得
    current_date = datetime.date(year, month, 1)

    # 月の最終日を取得
    last_day = calendar.monthrange(year, month)[1]

    # 営業日を取得するループ
    for _ in range(current_date.day, last_day+1):
        if jpbizday.is_bizday(current_date):  # jpbizdayを使用して営業日かどうかをチェック
            business_days.append(current_date.day)
        # 日付を1日進める
        current_date += datetime.timedelta(days=1)

    return business_days


# 営業日の日付を取得
business_days = get_business_days(year, month)
print(business_days)  # 営業日の日付を出力（整数の配列）


# ## 土日祝の日付のリスト

# In[8]:


def get_holi_days(year, month):
    holi_days = []
    business_days = get_business_days(year, month)
    
    holi_days = sorted(list(set(D) - set(business_days)))

    return holi_days


# 土日祝の日付を取得
holi_days = get_holi_days(year, month)
print(holi_days)  # 土日祝の日付を出力（整数の配列）


# ## 祝日のリスト

# In[9]:


import jpholiday
import datetime
import calendar

def get_only_holi_days(year, month):
    only_holi_days = []
    # 月の最初の日を取得
    current_date = datetime.date(year, month, 1)

    # 月の最終日を取得
    last_day = calendar.monthrange(year, month)[1]

    # 営業日を取得するループ
    for _ in range(current_date.day, last_day+1):
        if jpholiday.is_holiday(current_date):  # jpholidayを使用して営業日かどうかをチェック
            only_holi_days.append(current_date.day)
        # 日付を1日進める
        current_date += datetime.timedelta(days=1)

    return only_holi_days
    
only_holi_days = get_only_holi_days(year, month)


# In[10]:


print(only_holi_days)


# ## 月曜日のリスト（祝日は除く）

# In[11]:


import datetime

def get_mondays(year, month):
    mon_days = []
    
    # 月の最初の日を取得
    current_date = datetime.date(year, month, 1)

    # 月の最終日を取得
    last_day = calendar.monthrange(year, month)[1]
    
    # 営業日を取得するループ
    for _ in range(current_date.day, last_day+1):
        if current_date.weekday() == 0:  # weekday()を用いて月曜日かどうかをチェック
            mon_days.append(current_date.day)
        # 日付を1日進める
        current_date += datetime.timedelta(days=1)
    
    return mon_days

# 月曜日の日付を取得
mon_days = get_mondays(year, month)

mon_busi_days = []
mon_busi_days = sorted(list(set(mon_days) - set(only_holi_days)))
print(mon_busi_days) # 月曜日の日付を出力（整数の配列）


# ## 土曜日の日付のリスト(祝日は除く)

# In[12]:


import datetime

def get_saturdays(year, month):
    satur_days = []
    
    # 月の最初の日を取得
    current_date = datetime.date(year, month, 1)

    # 月の最終日を取得
    last_day = calendar.monthrange(year, month)[1]
    
    # 営業日を取得するループ
    for _ in range(current_date.day, last_day+1):
        if current_date.weekday() == 5:  # weekday()を用いて土曜日かどうかをチェック
            satur_days.append(current_date.day)
        # 日付を1日進める
        current_date += datetime.timedelta(days=1)
    
    return satur_days

# 土曜日の日付を取得
satur_days = get_saturdays(year, month)

satur_busi_days = []
satur_busi_days = sorted(list(set(satur_days) - set(only_holi_days)))
print(satur_busi_days) # 月曜日の日付を出力（整数の配列）


# ## 日曜日, 及び祝日の日付のリスト
# ## 日曜日の日付のリスト（祝日は除く）

# In[13]:


import datetime

def get_sundays(year, month):
    sun_days = []
    
    # 月の最初の日を取得
    current_date = datetime.date(year, month, 1)

    # 月の最終日を取得
    last_day = calendar.monthrange(year, month)[1]
    
    # 営業日を取得するループ
    for _ in range(current_date.day, last_day+1):
        if current_date.weekday() == 6:  # weekday()を用いて日曜日かどうかをチェック
            sun_days.append(current_date.day)
        # 日付を1日進める
        current_date += datetime.timedelta(days=1)
    
    return sun_days

# 日曜日の日付を取得
sun_days = get_sundays(year, month)

sun_busi_days = sorted(list(set(sun_days) - set(only_holi_days)))

sun_holi_days = sorted(list(set(only_holi_days) | set(sun_days)))
print(sun_busi_days)
print(sun_holi_days)


# In[14]:


print(satur_days)


# ## 日曜日と祝日の日付のリスト

# In[15]:


# def get_sundays(year, month):
#     sun_holi_days = []
    
#     sun_holi_days = sorted(list(set(holi_days) - set(satur_days)))
    
#     return sun_holi_days

# # 日曜日, 祝日の日付を取得
# sun_holi_days = get_sundays(year, month)
# print(sun_holi_days) # 日曜日, 祝日の日付の日時を取得


# ## 祝日の日付のリスト

# In[16]:


# def get_only_holi_days(year, month):
#     only_holi_days = []
    
#     only_holi_days = sorted(list(set(sun_holi_days) - set(sun_days)))

#     return only_holi_days


# # 祝日の日付を取得
# only_holi_days = get_only_holi_days(year, month)
# print(only_holi_days)  # 祝日の日付を出力（整数の配列）


# ## 土曜日の日付のリスト（祝日は除く）

# In[17]:


# satur_busi_days = [x for x in satur_days if not x in only_holi_days]
# print(satur_busi_days) # 土曜日の日付を出力（整数の配列）


# ## 平日・土曜日の日付のリスト

# In[18]:


#平日・土曜日の日付のリスト

busi_satur_days = []
busi_satur_days = sorted(business_days + satur_busi_days)

print(busi_satur_days)


# ## シフト

# In[19]:


# C: シフトの集合

# C[0]:公休 C[1]:特殊級 
# C[2]:ホ勤:FEデスク（10:00-18:00） C[3]:ホ勤:フィーチャー（10:00-18:00）
# C[4]:D勤:デスク（13:00-21:00）　
# C[5]: B勤:FEデスク（12:00-20:00） C[6]: B勤:フィーチャー（12:00-20:00）　
# C[7]:E勤:地二・フィーチャー（13:30-21:30）  C[8]:E勤:地一・地三（13:30-21:30） 
# C[9]:I勤:経二（16:00-23:30）  C[10]:I勤:A中（16:00-23:30）
# C[11]:J勤:国際（16:30 ~ 0:00） C[12]:J勤:内政（16:30 ~ 0:00） C[13]:J勤:総三（16:30 ~ 0:00）
# C[14]:W勤:二社（17:15-0:15）  C[15]:W勤:総二（17:15-0:15）
# C[16]:K勤:スポーツデスク補助（17:15~0:15）
# C[17]:L勤:総一・経一（18:00~1:00） C[18]:L勤:総一（18:00~1:00） C[19]:L勤:一社・総四（18:00~1:00） C[20]:L勤:一社（18:00~1:00） 
# C[21]:L勤:スポ一（18:00~1:00） C[22]:L勤:スポ二（18:00~1:00） C[23]:L勤:スポ三（18:00~1:00） C[24]:L勤:スポーツデスク（18:00~1:00）
#  C[25]:L勤:A硬（18:00~1:00） C[26]:L勤:A軟（18:00~1:00） C[27]:L勤:B（18:00~1:00） C[28]:L勤:総三（18:00~1:00）

C = ['公休', '特殊休', 
     'ホ:FEデスク', 'ホ:フィーチャー', 
     'D:デスク', 
     'B:FEデスク', 'B:フィーチャー', 
     'E:地二・フィーチャー', 'E:地一・地三', 
     'I:経二', 'I:A中', 
     'J:国際', 'J:内政', 'J:総三', 
     'W:二社', 'W:総二', 
     'K:スポーツデスク補助', 
     'L:総一・経一', 'L:総一', 'L:一社・総四', 'L:一社', 
     'L:スポ一', 'L:スポ二', 'L:スポ三', 'L:スポーツデスク',  
     'L:A硬', 'L:A軟', 'L:B', 'L:総三', ] 


# In[20]:


print(C[5:7])
HO_E = C[4:7] + C[9:]
print(HO_E)


# ### 休み希望

# In[21]:


import argparse
import sys

import csv


# 引数を取得
filename = sys.argv[1]
#filename = '238z.csv'
desired_vacation = []
# ファイルの内容を出力
with open(filename, "r") as f:
    reader = csv.reader(f)

    # ヘッダ行をスキップする
    next(reader, None)

    for row in reader:
        #print(row[1])
        # '['と']'を取り除き、カンマで分割してリストに変換
        cleaned_values = [value.strip() for value in row[1].strip("[]").split(",") if value.strip()]
        desired_vacation.append(list(map(int, cleaned_values)))


# In[22]:


#print(desired_vacation)  # 結果の出力


# In[23]:


#G: グループ


# In[24]:


# Q: 禁止シフト1

#H,J,L勤->ロ,ハ勤禁止（原則, 8時間以上間隔をあける）


# ## 数理モデル

# In[25]:


problem = pulp.LpProblem(sense=pulp.LpMinimize)


# ## 変数

# In[26]:


# x[m, d, c]: 職員 m が d 日の勤務 c であるかどうか

x = pulp.LpVariable.dicts('x', [(m, d, c) for m in M for d in D for c in C], cat='Binary')


# In[27]:


# その日の勤務はシフトのうち, いずれか1つ

for m in M:
    for d in D:
        problem += pulp.lpSum([x[m, d, c] for c in C]) == 1


# In[28]:


# y[m, d]:職員 m が d 日から連勤かどうか

y = pulp.LpVariable.dicts('y', [(m, d) for m in M for d in range(1, last_day_as_int)], cat='Binary')


# ## 制約式

# ## 新聞編集部整理班

# ### メンバー区分

# In[29]:


# デスク：FE, スポーツ, A硬, A軟
FE_member = M[1:5] + M[6:11] + M[12:17]

#デスク：A中
Atyuu_member = M[1:11] + M[12:17]

#デスク：B
B_member = M[0:4] + M[6:7]

#デスク：C
C_member = M[6:11] + M[12:17]

#デスク：D
D_member = M[0:5] + M[6:7]

# 2面組
twomen_member = M[8:11] + M[17:23]

# 面担
men_member = M[3:5] + M[6:11] + M[12:]

# デスク：FE　または, 面担
FE_men_member = M[1:5] + M[6:11] + M[12:]

# 副部長F以外
FB_ex = M[0:11] + M[12:]

# 能力：5
abi_5 = M[0:11]
# 能力：4
abi_4 = M[11:23]
# 能力：3
abi_3 = M[23:31]
# 能力：2
abi_2 = M[31:]

#能力：4以上
abi_45 = M[0:23]


# ## 制約　最低出社人数

# In[30]:


#制約
# 最低, 18人は出社する

for d in D:
        problem += pulp.lpSum([x[m, d, c] for c in C[2:] for m in M]) >= 18


# ### 制約　平日・土曜日

# In[31]:


print(busi_satur_days)


# In[32]:


# 制約(0-1)
# 平日・土曜日は, ホ1人（デスク：FE）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[2]] for m in FE_member]) >= 1


# In[33]:


# 制約(0-2)
# 平日・土曜日は, ホ1人（面担：フィーチャー）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[3]] for m in men_member]) >= 1


# In[34]:


# 制約(0-)
# 平日・土曜日は, D1人（デスク）＊不定期

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[4]] for m in M[0:17]]) <= 1


# In[35]:


# 制約(0-)
# 平日・土曜日は, E計2人（面担：地二・フィーチャー、地一・地三）
# 平日・土曜日は, E1人（面担：地二・フィーチャー）
# 2面組できる方から選出

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[7]] for m in twomen_member]) >= 1


# In[36]:


# 制約(0-)
# 平日・土曜日は, E計2人（面担：地二・フィーチャー、地一・地三）
# 平日・土曜日は, E1人（面担：地一・地三）
# 2面組できる方から選出

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[8]] for m in twomen_member]) >= 1


# In[37]:


# 制約(0-)
# 平日・土曜日は, I1人（面担：経二）＊土曜除く

for d in business_days:
    problem += pulp.lpSum([x[m, d, C[9]] for m in men_member]) >= 1


# In[38]:


# 制約(0-)
# 平日・土曜日は, J計3人（面担：国際、内政、総三）
# J1人（面担：国際）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[11]] for m in men_member]) >= 1


# In[39]:


# 制約(0-)
# 平日・土曜日は, J計3人（面担：国際、内政、総三）
# J1人（面担：内政）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[12]] for m in men_member]) >= 1


# In[40]:


# 制約(0-)
# 平日・土曜日は, J計3人（面担：国際、内政、総三）
# J1人（面担：総三）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[13]] for m in men_member]) >= 1


# In[41]:


# 制約(0-)
# 平日・土曜日は, W計2人（面担：二社、総二）
# W1人（面担：二社）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[14]] for m in men_member]) >= 1


# In[42]:


# 制約(0-)
# 平日・土曜日は, W計2人（面担：二社、総二）
# W1人（面担：総二）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[15]] for m in men_member]) >= 1


# In[43]:


# 制約(0-)
# 平日・土曜日は, L1人（面担：総一・経一）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[17]] for m in twomen_member]) >= 1


# In[44]:


# 制約(0-)
# 平日・土曜日は, L1人（面担：総一）＊土曜のみ

for d in satur_busi_days:
    problem += pulp.lpSum([x[m, d, C[18]] for m in men_member]) >= 1


# In[45]:


# 制約(0-)
# 平日・土曜日は, L1人（面担：一社・総四）＊月・土曜除く

not_mon_business_days = sorted(list(set(business_days) - set(mon_busi_days)))
print(not_mon_business_days)

for d in not_mon_business_days:
    problem += pulp.lpSum([x[m, d, C[19]] for m in men_member]) >= 1


# In[46]:


# 制約(0-)
# 平日・土曜日は, L1人（面担：一社）＊月・土曜のみ

mon_satur_days = sorted(mon_busi_days + satur_busi_days)

for d in mon_satur_days:
    problem += pulp.lpSum([x[m, d, C[20]] for m in men_member]) >= 1


# In[47]:


# 制約(0-)
# 平日・土曜日は, L計3人（面担：スポ一、スポ二、スポ三）
# L1人（面担：スポ一）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[21]] for m in men_member]) >= 1


# In[48]:


# 制約(0-)
# 平日・土曜日は, L3人（面担：スポ一、スポ二、スポ三）
# L1人（面担：スポ二）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[22]] for m in men_member]) >= 1


# In[49]:


# 制約(0-)
# 平日・土曜日は, L3人（面担：スポ一、スポ二、スポ三）
# L1人（面担：スポ三）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[23]] for m in men_member]) >= 1


# In[50]:


# 制約(0-)
# 平日・土曜日は, L1～2人（デスク：スポーツ）＊5個面以上になる場合2人

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[24]] for m in FE_member]) >= 2


# In[51]:


# 制約(0-)
# 平日・土曜日は, K1人（面担：スポーツデスク補助）

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[16]] for m in men_member]) <= 1


# In[52]:


# 制約(0-)
# 平日・土曜日は, I1人（デスク：A中）＊土曜除く = 営業日

for d in business_days:
    problem += pulp.lpSum([x[m, d, C[10]] for m in Atyuu_member]) >= 1


# In[53]:


# 制約(0-)
# 平日・土曜日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：A硬

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[25]] for m in FE_member]) >= 1


# In[54]:


# 制約(0-)
# 平日・土曜日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：A軟

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[26]] for m in FE_member]) >= 1


# In[55]:


# 制約(0-)
# 平日・土曜日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：B

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[27]] for m in B_member]) >= 1


# In[56]:


# 制約(0-)
# 平日・土曜日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：総三 = Cとしている

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, C[28]] for m in C_member]) >= 1


# In[57]:


# 制約(0-)
# 平日・土曜日は, Bはなし

for d in busi_satur_days:
    problem += pulp.lpSum([x[m, d, c] for c in C[5:7] for m in M]) == 0


# ## 制約　日曜日, 祝日

# In[58]:


# 制約(1-)
# 日曜日, 及び祝日は, B1人（デスク：FE）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[5]] for m in FE_member]) >= 1


# In[59]:


# 制約(1-)
# 日曜日, 及び祝日は, B1人（面担：フィーチャー）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[6]] for m in men_member]) >= 1


# In[60]:


# 制約(1-)
# 日曜日, 及び祝日は, E計2人（面担：地二、地一・地三）
# E1人（面担：地二）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[7]] for m in men_member]) >= 1


# In[61]:


# 制約(1-)
# 日曜日, 及び祝日は, E計2人（面担：地二、地一・地三）
# E1人（面担：地一・地三）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[8]] for m in men_member]) >= 1


# In[62]:


# 制約(1-)
# 日曜日, 及び祝日は, J計3人（面担：国際、内政、総三）
# J1人（面担：国際）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[11]] for m in men_member]) >= 1


# In[63]:


# 制約(1-)
# 日曜日, 及び祝日は, J計3人（面担：国際、内政、総三）
# J1人（面担：内政）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[12]] for m in men_member]) >= 1


# In[64]:


# 制約(1-)
# 日曜日, 及び祝日は, J計3人（面担：国際、内政、総三）
# J1人（面担：総三）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[13]] for m in men_member]) >= 1


# In[65]:


# 制約(1-)
# 日曜日, 及び祝日は, W計2人（面担：二社、総二）
# W1人（面担：二社）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[14]] for m in men_member]) >= 1


# In[66]:


# 制約(1-)
# 日曜日, 及び祝日は, W計2人（面担：二社、総二）
# W1人（面担：総二）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[15]] for m in men_member]) >= 1


# In[67]:


# 制約(1-)
# 日曜日, 及び祝日は, L計5人（面担：総一、一社、スポ一、スポ二、スポ三）＊日曜のみ
# L1人（面担：総一）

for d in sun_busi_days:
    problem += pulp.lpSum([x[m, d, C[18]] for m in men_member]) >= 1


# In[68]:


# 制約(1-)
# 日曜日, 及び祝日は, L1人（面担：総一・経一）＊祝日のみ

for d in only_holi_days:
    problem += pulp.lpSum([x[m, d, C[17]] for m in twomen_member]) >= 1


# In[69]:


# 制約(1-)
# 日曜日, 及び祝日は, L計5人（面担：総一、一社、スポ一、スポ二、スポ三）＊日曜のみ
# L1人（面担：一社）

for d in sun_busi_days:
    problem += pulp.lpSum([x[m, d, C[20]] for m in men_member]) >= 1


# In[70]:


# 制約(1-)
# 日曜日, 及び祝日は, L計5人（面担：総一、一社、スポ一、スポ二、スポ三）＊日曜のみ
# L1人（面担：スポ一）

for d in sun_busi_days:
    problem += pulp.lpSum([x[m, d, C[21]] for m in men_member]) >= 1


# In[71]:


# 制約(1-)
# 日曜日, 及び祝日は, L計5人（面担：総一、一社、スポ一、スポ二、スポ三）＊日曜のみ
# L1人（面担：スポ二）

for d in sun_busi_days:
    problem += pulp.lpSum([x[m, d, C[22]] for m in men_member]) >= 1


# In[72]:


# 制約(1-)
# 日曜日, 及び祝日は, L計5人（面担：総一、一社、スポ一、スポ二、スポ三）＊日曜のみ
# L1人（面担：スポ三）

for d in sun_busi_days:
    problem += pulp.lpSum([x[m, d, C[23]] for m in men_member]) >= 1


# In[73]:


# 制約(1-)
# 日曜日, 及び祝日は, L1～2人（デスク：スポーツ）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[24]] for m in FE_member]) >= 2


# In[74]:


# 制約(0-)
# 日曜日, 及び祝日は, K1人（面担：スポーツデスク補助）

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[16]] for m in men_member]) <= 1


# In[75]:


# 制約(1-)
# 日曜日, 及び祝日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：A硬

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[25]] for m in FE_member]) >= 1


# In[76]:


# 制約(1-)
# 日曜日, 及び祝日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：A軟

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[26]] for m in FE_member]) >= 1


# In[77]:


# 制約(1-)
# 日曜日, 及び祝日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：B

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[27]] for m in B_member]) >= 1


# In[78]:


# 制約(1-)
# 日曜日, 及び祝日は, L4人（デスク：A硬、A軟、B、総三）
# デスク：総三 = Cとしている

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[28]] for m in C_member]) >= 1


# In[79]:


# 制約(0-)
# 日曜日, 及び祝日は, D1人（デスク）＊不定期

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[4]] for m in M[0:17]]) <= 1


# In[80]:


# 制約(0-)
# 日曜日, 及び祝日は, ホはなし

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, c] for c in C[2:4] for m in M]) == 0


# ## 制約　休み関連

# In[81]:


# 制約(2-1)
# 公休を, 計6回確保する

for m in M:
    problem += pulp.lpSum([x[m, d, C[0]] for d in D]) == 6


# In[82]:


# 制約(2-2)
# 特殊級を, 計3回以上確保する

for m in M:
    problem += pulp.lpSum([x[m, d, C[1]] for d in D]) >= 3


# In[83]:


# 制約(3-3)
# 特殊級を, 計4回までに制限

for m in M:
    problem += pulp.lpSum([x[m, d, C[1]] for d in D]) <= 5


# In[84]:


# 制約(5) <- かなり, 相性が悪そう, 希望の休みと休みの制約のみでも最適解を見つけられていない
# 連勤希望の人はできるだけ連勤になるように設定
# 連休を2回以上設定

for m in M:
    for d in D[:-1]:
        problem += x[m, d, C[0]] + x[m, d, C[1]] + x[m, d+1, C[0]] + x[m, d+1, C[1]] - y[m, d] <= 1
        problem += x[m, d, C[0]] + x[m, d, C[1]] + x[m, d+1, C[0]] + x[m, d+1, C[1]] - y[m, d] * 2 >= 0
    
for m in M:
    problem += pulp.lpSum([y[m, d] for d in D[:-1]]) >= 2 #<--なぜか2だと連休が2回以上取れない場合が発生


# ## 制約　勤務制限

# In[85]:


# 制約(4)
# 連続勤務は5日までしか許されない

for m in M:
    for d in D[5:]:        
        problem += pulp.lpSum([x[m, d - h, c] for h in range(5 + 1) for c in C[2:]]) <= 5


# In[86]:


# # 制約(5)
# # ホとBの前日には夜勤はNG（なるべく休日の後に組み込む） <- 相性悪め

# HO_B = C[2:4] + C[5:7]

# for m in M:
#     for d in D[1:]:
#         problem += pulp.lpSum([x[m, d-1 , c1] + x[m, d, c2] for c1 in C[9:] for c2 in HO_B])  <= 1


# In[87]:


# # 制約(5)
# # ホとBの前日には夜勤はNG

# #なるべく休日の後に組み込む <- 相性悪め

# for m in M:
#     for d in D[1:]:
#         problem += pulp.lpSum([x[m, d-1 , C[0]] + x[m, d-1 , C[1]] + x[m, d, C[2]] + x[m, d, C[4]]])  >= 2


# In[88]:


# # 制約(5)
# #DとEの前日も、なるべく夜勤は入れない <- 相性悪め

# D_E = C[4:5] + C[7:9]

# for m in M:
#     for d in D[1:]:
#         problem += pulp.lpSum([x[m, d-1 , c1] + x[m, d, c2] for c1 in C[9:] for c2 in D_E ])  <= 1


# In[89]:


# 制約(6)
# 部員Fはホ, E勤のみ勤務可能　= 部員Fはホ, E勤以外は禁止

HO_E = C[4:7] + C[9:]

for d in D:
    problem += pulp.lpSum([x[M[22], d, c] for c in HO_E]) == 0


# In[90]:


# 制約(6)
# 部員Mはホ, E勤のみ勤務可能　= 部員Mはホ, E勤以外は禁止

HO_E = C[4:7] + C[9:]

for d in D:
    problem += pulp.lpSum([x[M[29], d, c] for c in HO_E]) == 0


# ## 制約　能力関連

# In[91]:


# 制約
# チームごとの総合力を合わせる
# チーム硬(総一, 内政, 国際)
# 能力4以上の人が一人以上

for d in D:
    problem += pulp.lpSum([x[m, d, C[11]] + x[m, d, C[12]] + x[m, d, C[17]] + x[m, d, C[18]] for m in abi_45]) >= 1


# In[92]:


# 制約
# チームごとの総合力を合わせる
# チーム軟(総二, 一社, 二社)
# 能力4以上の人が一人以上

for d in D:
    problem += pulp.lpSum([x[m, d, C[14]] + x[m, d, C[15]] + x[m, d, C[19]] + x[m, d, C[20]] for m in abi_45]) >= 1


# In[93]:


# 制約
# チームごとの総合力を合わせる
# チーム中(総三, 経一, 経二)
# 能力4以上の人が一人以上

for d in D:
    problem += pulp.lpSum([x[m, d, C[9]] + x[m, d, C[13]] + x[m, d, C[17]] + x[m, d, C[18]] for m in abi_45]) >= 1


# In[94]:


# 制約
# チームごとの総合力を合わせる
# チーム地方(地一, 地二, 地三)
# 能力4以上の人が一人以上

for d in D:
    problem += pulp.lpSum([x[m, d, C[7]] + x[m, d, C[8]] for m in abi_45]) >= 1


# In[95]:


# 制約
# チームごとの総合力を合わせる
# チームスポーツ(スポ一, スポ二, スポ三)
# 能力4以上の人が一人以上

for d in D:
    problem += pulp.lpSum([x[m, d, C[21]] + x[m, d, C[22]] + x[m, d, C[23]]  for m in abi_45]) >= 1


# In[96]:


# 制約
# チームごとの総合力を合わせる
# チーム硬(総一, 内政, 国際)
# 能力2の人が一人以下

for d in D:
    problem += pulp.lpSum([x[m, d, C[11]] + x[m, d, C[12]] + x[m, d, C[17]] + x[m, d, C[18]] for m in abi_2]) <= 1


# In[97]:


# 制約
# チームごとの総合力を合わせる
# チーム軟(総二, 一社, 二社)
# 能力2の人が一人以下

for d in D:
    problem += pulp.lpSum([x[m, d, C[14]] + x[m, d, C[15]] + x[m, d, C[19]] + x[m, d, C[20]]  for m in abi_2]) <= 1


# In[98]:


# 制約
# チームごとの総合力を合わせる
# チーム中(総三, 経一, 経二)
# 能力2の人が一人以下

for d in D:
    problem += pulp.lpSum([x[m, d, C[9]] + x[m, d, C[13]] + x[m, d, C[17]] + x[m, d, C[18]] for m in abi_2]) <= 1


# In[99]:


# 制約
# チームごとの総合力を合わせる
# チーム地方(地一, 地二, 地三)
# 能力2の人が一人以下

for d in D:
    problem += pulp.lpSum([x[m, d, C[7]] + x[m, d, C[8]] for m in abi_2]) <= 1


# In[100]:


# 制約
# チームごとの総合力を合わせる
# チームスポーツ(スポ一, スポ二, スポ三)
# 能力2の人が一人以下

for d in D:
    problem += pulp.lpSum([x[m, d, C[21]] + x[m, d, C[22]] + x[m, d, C[23]]  for m in abi_2]) <= 1


# In[101]:


pulp.LpStatus[problem.solve()]


# In[102]:


# 1行目に日にち，1列目に従業員が書かれたシフトの結果をcsvファイルに出力
from datetime import datetime
import pytz

df = pd.DataFrame(index=D, columns=M)

for d in D:
    for m in M:
        filtered_list = [c for c in C if value(x[m, d, c]) == 1]
        if filtered_list:  # リストが空でない場合
            df.loc[d, m] = filtered_list[0]
        else:
            df.loc[d, m] = '条件に一致する要素は見つかりませんでした'

# 各列ごとに"公休"などの数を数えて新しい行を追加
for c in C:
    count_public_holiday = df.apply(lambda x: x[x == c].count())
    df.loc[c] = count_public_holiday
# 行ごとに"公休"の数を数えて新しい列を追加
for c in C:
    df[c] = df.apply(lambda row: row[row == c].count(), axis=1)

# 転置して出力

# UTCで現在時刻を取得
current_datetime_utc = datetime.utcnow()
# 日本のタイムゾーンを定義
japan_timezone = pytz.timezone("Asia/Tokyo")
# UTCから日本時間に変換
current_datetime_jst = current_datetime_utc.astimezone(japan_timezone)
# 指定のフォーマットで日時を文字列に変換
formatted_datetime = current_datetime_jst.strftime("%Y%m%d_%H%M%S")
output_filename = f"news_result_year{year}_month{month}_{formatted_datetime}.csv"
df.T.to_csv("./data/" + output_filename, encoding="utf-8")
df.T.to_csv("./data/dl/" + output_filename, encoding="shift_jis")

f = open("searchpath.txt", 'w')
f.write(output_filename)
f.close()
