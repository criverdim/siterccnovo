<?php $__env->startComponent('mail::message'); ?>
# Novo Contato Recebido

**Nome:** <?php echo e($contactData['name'] ?? ''); ?>


**E-mail:** <?php echo e($contactData['email'] ?? ''); ?>


**Telefone:** <?php echo e($contactData['phone'] ?? '—'); ?>


**Empresa:** <?php echo e($contactData['company'] ?? '—'); ?>


**Assunto:** <?php echo e($contactData['subject'] ?? '—'); ?>


**Mensagem:**

<?php echo e($contactData['message'] ?? ''); ?>


Recebido em <?php echo e($contactData['created_at'] ?? now()->format('d/m/Y H:i')); ?>.

<?php echo $__env->renderComponent(); ?>
<?php /**PATH /var/www/html/rcc-system/resources/views/emails/contact-admin.blade.php ENDPATH**/ ?>