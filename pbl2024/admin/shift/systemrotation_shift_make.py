#!/usr/bin/env python
# coding: utf-8

# In[1]:


from pulp import *
import pytz

# In[2]:


import pulp


# In[3]:


import pandas as pd


# ## パラメーター

# ### 職員

# In[4]:


# M: 職員の集合
M = ['臨時・派遣A', '臨時・派遣B', '臨時・派遣C', '臨時・派遣D', '臨時・派遣E']


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


# ## 土曜日の日付のリスト

# In[9]:


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
print(satur_days) # 土曜日の日付を出力（整数の配列）


# ## 日曜日と祝日の日付のリスト

# In[10]:


def get_sundays(year, month):
    sun_holi_days = []
    
    sun_holi_days = sorted(list(set(holi_days) - set(satur_days)))
    
    return sun_holi_days

# 日曜日, 祝日の日付を取得
sun_holi_days = get_sundays(year, month)
print(sun_holi_days) # 日曜日, 祝日の日付の日時を取得


# ## シフト

# In[11]:


# C: シフトの集合

# C[0]:公休 C[1]:特殊級 C[2]:ロ勤(8:30 ~ 16:30)  C[3]:ハ勤(9:00 ~ 17:00)　C[4]: ホ勤(10:00 ~ 18:00)　C[5]:A勤(11:30 ~ 19:30)　C[6]:H勤(15:30 ~23:00) C[7]:J勤(16:30 ~ 0:00) C[8]:L勤(18:00 ~ 1:00)
C = ['公休', '特殊休', 'ロ', 'ハ', 'ホ', 'A', 'H', 'J', 'L'] 


# ### 休み希望

# In[12]:


import argparse
import sys

import csv


# 引数を取得
filename = sys.argv[1]
# filename = 'degitalstreaming_desired_vacation_pre.csv'
desired_vacation = []
# ファイルの内容を出力
with open(filename, "r") as f:
    reader = csv.reader(f)

    # ヘッダ行をスキップする
    next(reader, None)

    for row in reader:
        print(row[1])
        # '['と']'を取り除き、カンマで分割してリストに変換
        cleaned_values = [value.strip() for value in row[1].strip("[]").split(",") if value.strip()]
        desired_vacation.append(list(map(int, cleaned_values)))        


# In[13]:


#print(desired_vacation)  # 結果の出力


# In[14]:


#G: グループ


# In[15]:


# Q: 禁止シフト1

#H,J,L勤->ロ,ハ勤禁止（原則, 8時間以上間隔をあける）


# ## 数理モデル

# In[16]:


problem = pulp.LpProblem(sense=pulp.LpMinimize)


# ## 変数

# In[17]:


# x[m, d, c]: 職員 m が d 日の勤務 c であるかどうか

x = pulp.LpVariable.dicts('x', [(m, d, c) for m in M for d in D for c in C], cat='Binary')


# In[18]:


# その日の勤務はシフトのうち, いずれか1つ

for m in M:
    for d in D:
        problem += pulp.lpSum([x[m, d, c] for c in C]) == 1


# In[19]:


# y[m, d]:職員 m が d 日から連勤かどうか

y = pulp.LpVariable.dicts('y', [(m, d) for m in M for d in range(1, last_day_as_int)], cat='Binary')


# ## 制約式

# ## システム部ローテ業務

# In[20]:


# 制約(0-1)
# 平日はロ勤：1人, J勤：1人, L勤：1人
# ロ勤：1人

for d in business_days:
    problem += pulp.lpSum([x[m, d, C[2]] for m in M]) >= 1


# In[21]:


# 制約(0-2)
# 平日はロ勤：1人, J勤：1人, L勤：1人
# J勤：1人

for d in business_days:
    problem += pulp.lpSum([x[m, d, C[7]] for m in M]) >= 1


# In[22]:


# 制約(0-3)
# 平日はロ勤：1人, J勤：1人, L勤：1人
# L勤：1人

for d in business_days:
    problem += pulp.lpSum([x[m, d, C[8]] for m in M]) >= 1


# In[23]:


# 制約(1-1)
# 土曜日はハ勤：1人, ホ勤：1人, J勤：1人, L勤：1人
# ハ勤

for d in satur_days:
    problem += pulp.lpSum([x[m, d, C[3]] for m in M]) >= 1


# In[24]:


# 制約(1-2)
# 土曜日はハ勤：1人, ホ勤：1人, J勤：1人, L勤：1人
# ホ勤

for d in satur_days:
    problem += pulp.lpSum([x[m, d, C[4]] for m in M]) >= 1


# In[25]:


# 制約(1-3)
# 土曜日はハ勤：1人, ホ勤：1人, J勤：1人, L勤：1人
# J勤

for d in satur_days:
    problem += pulp.lpSum([x[m, d, C[7]] for m in M]) >= 1


# In[26]:


# 制約(1-4)
# 土曜日はハ勤：1人, ホ勤：1人, J勤：1人, L勤：1人
# L勤

for d in satur_days:
    problem += pulp.lpSum([x[m, d, C[8]] for m in M]) >= 1


# In[27]:


# 制約(2-1)
# 日曜日及び祝日はA勤：1人, H勤：1人, L勤：1人
# A勤

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[5]] for m in M]) >= 1


# In[28]:


# 制約(2-2)
# 日曜日及び祝日はA勤：1人, H勤：1人, L勤：1人
# H勤

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[6]] for m in M]) >= 1


# In[29]:


# 制約(2-3)
# 日曜日及び祝日はA勤：1人, H勤：1人, L勤：1人
# L勤

for d in sun_holi_days:
    problem += pulp.lpSum([x[m, d, C[8]] for m in M]) >= 1


# In[30]:


# 制約(3-1)
# 公休を, 計6回確保する

for m in M:
    problem += pulp.lpSum([x[m, d, C[0]] for d in D]) == 6


# In[31]:


# 制約(3-2)
# 特殊級を, 計3回以上確保する

for m in M:
    problem += pulp.lpSum([x[m, d, C[1]] for d in D]) >= 3


# In[32]:


# 制約(3-3)
# 特殊級を, 計4回までに制限

for m in M:
    problem += pulp.lpSum([x[m, d, C[1]] for d in D]) <= 4


# In[33]:


# 制約(4)
# 連続勤務は5日までしか許されない

for m in M:
    for d in D[5:]:        
        problem += pulp.lpSum([x[m, d - h, c] for h in range(5 + 1) for c in C[2:]]) <= 5


# In[34]:


# 制約(5)
# H, J, L勤->ロ, ハ勤は禁止

for m in M:
    for d in D[1:]:
        problem += pulp.lpSum([x[m, d-1 , C[6]] + x[m, d-1 , C[7]] + x[m, d-1 , C[8]] + x[m, d, C[2]] + x[m, d, C[3]]])  <= 1


# In[35]:


# 制約(6-1)
# 組み合わせ制限
# 臨時・派遣Bと臨時・派遣Dは同時に勤務不可 <- 同日勤務不可なのか, 時間帯さえ被っていなければ大丈夫なのか不明
# 現在, 同日は禁止において実装

for d in D:
    for c in C[2:]:
        problem += pulp.lpSum([x[M[1], d, c] + x[M[3], d, c]]) <= 1


# In[36]:


# 制約(6-2)
# 組み合わせ制限
# 臨時・派遣Bと臨時・派遣Eは同時に勤務不可 <- 同日勤務不可なのか, 時間帯さえ被っていなければ大丈夫なのか不明
# 現在, 同日は禁止において実装

for d in D:
    for c in C[2:]:
        problem += pulp.lpSum([x[M[1], d, c] + x[M[4], d, c]]) <= 1


# In[37]:


# 制約(7)
# 臨時・派遣Eはロ, ハ, ホ, A勤のみ勤務可能　= 臨時・派遣EはH, J, L勤は禁止
for d in D:
    problem += pulp.lpSum([x[M[4], d, c] for c in C[6:]]) == 0


# In[38]:


# 制約(8) <- かなり, 相性が悪そう, 希望の休みと休みの制約のみでも最適解を見つけられていない
# 連勤希望の人はできるだけ連勤になるように設定
# 連休を2回以上設定

# 連休を希望しているか否か
# consecutive_holidays = [1, 1, 1, 0, 1]

# for i in range(0, len(M)):
#     if consecutive_holidays[i] == 1:
#         for d in D[:-1]:
#             problem += x[M[i], d, C[0]] + x[M[i], d, C[1]] + x[M[i], d+1, C[0]] + x[M[i], d+1, C[1]] - y[M[i], d] <= 1
#             problem += x[M[i], d, C[0]] + x[M[i], d, C[1]] + x[M[i], d+1, C[0]] + x[M[i], d+1, C[1]] - y[M[i], d] * 2 >= 0
#     else:
#         for d in D[:-1]:
#             problem += y[M[i], d] == 2
    
#     problem += pulp.lpSum([y[m, d] for d in D[:-1]]) >= 2   


# In[39]:


pulp.LpStatus[problem.solve()]


# In[40]:


print(pulp.LpStatus[problem.solve()])


# In[41]:


# for p in M:
#     buf = []    
#     for d in D:
#         for c in C:
#             if x[p, d, c].value():
#                 buf.append(f' {c}')


# In[42]:


# print(' 社員名, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30, 公休, 特殊級, ハ勤, ト勤, F勤')
# for p in M:
#     buf = []    
#     for d in D:
#         for c in C:
#             if x[p, d, c].value():
#                 buf.append(f' {c}')
#     print(f"{p},{','.join(buf)},{buf.count(' 公休'): 4d},{buf.count(' 特殊休'): 4d}, {buf.count(' ロ'): 4d}, {buf.count(' ハ'): 4d}, {buf.count(' ホ'): 4d}, {buf.count(' A'): 4d}")
# for c in C[0:]:
#     buf = []
#     for d in D:
#         buf.append(f" {str(int(sum([x[p, d, c].value() for p in M])))}")
#     print(f"{c}:{','.join(buf)}")


# In[43]:


# 1行目に日にち，1列目に従業員が書かれたシフトの結果をcsvファイルに出力
from datetime import datetime

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
output_filename = f"sys_result_year{year}_month{month}_{formatted_datetime}.csv"
df.T.to_csv("./data/" + output_filename, encoding="utf-8")
df.T.to_csv("./data/dl/" + output_filename, encoding="shift_jis")

f = open("searchpath.txt", 'w')
f.write(output_filename)
f.close()
