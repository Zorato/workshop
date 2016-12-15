<?php

class WishlistService {

    protected $products;

    public function addToList($product)
    {
        $this->products[] = $product;
    }

    public function send($customer)
    {
        $email = $customer->getEmail();

        $mailer = new PHPMailer();
        $mailer->addAddress($email, $customer->getFullName());
        $mailer->Subject = 'Your wishlist is here!';
        $mailer->Body = render('emails/wishlist.phtml', ['products' => $this->products]);
        $mailer->setFrom('no-reply@mystore.com');

        return $mailer->send();
    }

}