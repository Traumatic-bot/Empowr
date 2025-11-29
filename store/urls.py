from django.urls import path
from . import views

urlpatterns = [
    path('', views.index, name='index'),
    path('category/<int:category_id>/', views.category_view, name='category'),
    path('login.html', views.login_view, name='login'),
    path('signup.html', views.signup_view, name='signup'),
]
