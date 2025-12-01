from django.urls import path
from django.contrib.auth import views as auth_views
from . import views

urlpatterns = [
    path('', views.index, name='index'),
    path('category/<int:category_id>/', views.category_view, name='category'),

    path('login/',  auth_views.LoginView.as_view(template_name='login.html'), name='login'),
    path('logout/', views.logout_view, name='logout'),



    path('signup/', views.signup_view, name='signup'),
    path('cart/', views.cart_view, name='cart'),
]
