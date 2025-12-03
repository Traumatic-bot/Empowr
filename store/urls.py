from django.urls import path
from django.contrib.auth import views as auth_views
from django.views.generic import TemplateView
from . import views

urlpatterns = [
    path("", views.index, name="index"),
    path("category/<int:category_id>/", views.category_view, name="category"),

    path("login/", auth_views.LoginView.as_view(
        template_name="account/login.html"
    ), name="login"),
    path("logout/", views.logout_view, name="logout"),
    path("signup/", views.signup_view, name="signup"),
    path("checkout/", views.checkout_view, name="checkout"),
    path("checkout/payment/", views.checkout_payment_view, name="checkout_payment"),
    path("checkout/complete/", views.checkout_complete_view, name="checkout_complete"),

    path("account/", TemplateView.as_view(template_name="account/dashboard2.html"), name="test"),
    path("account/order-history/", TemplateView.as_view(template_name="account/order_history.html"), name="order_history"),
    path("account/personal-details/", TemplateView.as_view(template_name="account/personal_details.html"), name="personal_details"),
    path("account/address-book/", TemplateView.as_view(template_name="account/address_book.html"), name="address_book"),
    path("account/payment-methods/", TemplateView.as_view(template_name="account/payment_methods.html"), name="payment_methods"),

    path("about-us/", TemplateView.as_view(template_name="store/about_us.html"), name="about_us"),
    path("contact-us/", views.contact_us, name="contact_us"),

    path("dashboard/", views.dashboard_router, name="dashboard"),
    path("staff/dashboard/", views.staff_dashboard, name="staff_dashboard"),
    path("account/dashboard/", views.customer_dashboard, name="customer_dashboard"),
]
