from django.conf.urls.defaults import patterns, include, url

# Uncomment the next two lines to enable the admin:
from django.contrib import admin
admin.autodiscover()

urlpatterns = patterns('',
	(r'^$', 'home.views.index'),
    (r'^contact/quote', 'home.views.send_quote'),
    (r'^contact/email', 'home.views.send_email'),
    (r'^contact', 'home.views.contact'),
    (r'^portfolio','home.views.portfolio'),
    (r'^projects','home.views.projects'),
    (r'^profile','home.views.about'),
    (r'^resume','home.views.resume'),
    (r'^services','home.views.services'),
	(r'^site_media/(?P<path>.*)$', 'django.views.static.serve',
        {'document_root': 'e:\\dev\\django\\ryanguthrie\\django\\ryang\\static'}),
    # Examples:
    #url(r'^$', 'home.views.home', name='home'),
    # url(r'^ryang/', include('ryang.foo.urls')),

    # Uncomment the admin/doc line below to enable admin documentation:
    # url(r'^admin/doc/', include('django.contrib.admindocs.urls')),

    # Uncomment the next line to enable the admin:
     url(r'^admin/', include(admin.site.urls)),
)
