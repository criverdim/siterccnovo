<?php $__env->startComponent('mail::message'); ?>
# Recebemos sua mensagem

Olá <?php echo e($contactData['name'] ?? ''); ?>,

Obrigado por entrar em contato com o RCC System.
Recebemos sua mensagem com o assunto **<?php echo e($contactData['subject'] ?? '—'); ?>** e em breve nossa equipe retornará.

Resumo:

**Mensagem:**
<?php echo e($contactData['message'] ?? ''); ?>


Se precisar complementar, responda este e-mail.

Atenciosamente,
RCC System

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /var/www/html/rcc-system/resources/views/emails/contact-confirmation.blade.php ENDPATH**/ ?>