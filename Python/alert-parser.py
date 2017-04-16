import re
import urllib.request
from datetime import datetime, timedelta

class Alert:
	def __init__(self, ends, alert):
		self.ends = ends
		self.location = alert[1]
		self.type = alert[2]
		self.credits = alert[4]
		if alert[5] == '':
			self.reward = 'NONE'
			self.rewardType = 'NONE'
		else:
			self.reward = alert[5]
			self.rewardType = ''

class Alerts:
	URL = 'https://www.twitter.com/warframealerts'
	alerts = []

	def __init__(self):
		self.get_alerts()

	def get_data(self):
		headers = {}
		headers['User-Agent'] = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36'
		req = urllib.request.Request(self.URL, headers=headers)
		try:
			resp = urllib.request.urlopen(req)
		except Exception as e:
			print(str(e))
		finally:
			return str(resp.read())

	def get_alerts(self):
		data = self.get_data()
		self.sort_alerts(data)

	def sort_alerts(self, data):
		alert_pat = r'class="ProfileTweet-timestamp.*?title="(.+?)".+?'
		alert_pat += r'([\w\d]+\s[()\w\d]+):\s([\w\d\s]+)\s-\s(\d\d?)[mh]\s-\s(\d+cr)'
		alert_pat += r'\s?-?\s?([\d\s\w]+\([\w\s]+\))?'
		temp = re.findall(alert_pat, data)
		now = datetime.now()

		# checks timeleft on all alerts
		for alert in temp:
			posted = datetime.strptime(alert[0], '%I:%M %p - %d %b %Y')
			duration = timedelta(hours=3, minutes=int(alert[3])) 
			ends = (posted + duration) - now
			if ends.total_seconds() >= 0:
				new = Alert(ends, alert)
				self.alerts.append(new)

	def print_active(self):
		for alert in self.alerts:
			print('Location:', alert.location)
			print('  Time Left:', alert.ends)
			print('  Credits:', alert.credits)
			print('  Reward:', alert.reward)


alerts = Alerts()
alerts.print_active()