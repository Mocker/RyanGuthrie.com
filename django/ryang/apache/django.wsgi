import os
import sys

path = '/var/www/home/ryang/django'
if path not in sys.path:
	sys.path.append(path)
path = '/var/www/home/ryang/django/ryang'
if path not in sys.path:
	sys.path.append(path)


os.environ['DJANGO_SETTINGS_MODULE'] = 'ryang.settings'

import django.core.handlers.wsgi
application = django.core.handlers.wsgi.WSGIHandler()

