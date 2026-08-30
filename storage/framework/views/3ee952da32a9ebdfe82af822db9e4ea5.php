<?php $__env->startSection('title', 'Upgrade to Premium'); ?>
<?php $__env->startSection('page-title', 'Scholarify Premium'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-8">

    
    <?php if($user->isPremium()): ?>
    <div class="card p-5 bg-gradient-to-r from-yellow-50 to-amber-50 border-yellow-300">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-400 flex items-center justify-center text-xl flex-shrink-0">⭐</div>
            <div class="flex-1">
                <p class="font-bold text-yellow-900 text-lg">You are a Premium Member!</p>
                <p class="text-sm text-yellow-700">
                    Your plan is active until
                    <strong><?php echo e($user->plan_expires_at?->format('d F Y') ?? 'Unlimited'); ?></strong>
                </p>
            </div>
            <span class="badge bg-yellow-400 text-yellow-900 text-sm px-3 py-1">✓ Active</span>
        </div>
    </div>
    <?php else: ?>
    
    <div class="card p-5 bg-blue-50 border-blue-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl flex-shrink-0">🎓</div>
            <div>
                <p class="font-semibold text-blue-900">You are on the Free Plan</p>
                <p class="text-sm text-blue-700">Limited to <?php echo e(\App\Models\User::FREE_REC_LIMIT); ?> recommendations · Some filters locked</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card overflow-hidden">
        <div class="grid grid-cols-3 divide-x divide-gray-100">
            
            <div class="p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Features</p>
                <?php $__currentLoopData = [
                    'Personalized Recommendations',
                    'University Search & Filters',
                    'Region-Based Filter',
                    'Scholarship Search',
                    'Eligible-Only Filter',
                    'Full Application Guidance',
                    'Score Breakdown Details',
                    'Unlimited Saved Applications',
                    'Deadline Email Reminders',
                    'Priority Support',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="text-sm text-gray-600 py-2.5 border-b border-gray-50 last:border-0"><?php echo e($feat); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="p-6 bg-gray-50">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Free</p>
                <?php $__currentLoopData = ['Up to 5 only','✓','✗','✓','✗','Limited','✗','Up to 3','✗','✗']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="text-sm py-2.5 border-b border-gray-100 last:border-0 <?php echo e($v === '✗' ? 'text-red-400' : ($v === '✓' ? 'text-green-600 font-semibold' : 'text-gray-600')); ?>">
                    <?php echo e($v); ?>

                </p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="p-6 bg-gradient-to-b from-yellow-50 to-amber-50">
                <p class="text-xs font-bold text-amber-600 uppercase tracking-wide mb-4">⭐ Premium</p>
                <?php $__currentLoopData = ['Unlimited (50+)','✓','✓','✓','✓','✓','✓','Unlimited','✓','✓']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="text-sm py-2.5 border-b border-amber-100 last:border-0 font-semibold text-green-700"><?php echo e($v); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <?php if (! ($user->isPremium())): ?>
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-5 text-center">Choose Your Plan</h2>
        <form method="POST" action="<?php echo e(route('student.subscription.checkout')); ?>" id="checkoutForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="duration_months" id="selectedMonths" value="3">
            <input type="hidden" name="payment_method"  id="selectedMethod"  value="sslcommerz">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <?php $__currentLoopData = $pricing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $months => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button type="button" onclick="selectPlan(<?php echo e($months); ?>)"
                    id="plan-<?php echo e($months); ?>"
                    class="plan-btn card p-4 text-center cursor-pointer hover:border-yellow-400 hover:shadow-md transition-all relative
                           <?php echo e($months === 3 ? 'border-yellow-400 ring-2 ring-yellow-300 shadow-md' : ''); ?>">
                    <?php if($plan['save']): ?>
                    <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-green-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        Save <?php echo e($plan['save']); ?>

                    </span>
                    <?php endif; ?>
                    <p class="text-2xl font-extrabold text-gray-900">৳<?php echo e($plan['bdt']); ?></p>
                    <p class="text-sm text-gray-500 mt-1"><?php echo e($plan['label']); ?></p>
                    <p class="text-xs text-gray-400 mt-0.5">৳<?php echo e(round($plan['bdt']/$months)); ?>/mo</p>
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="card p-5 mb-5">
                <p class="text-sm font-semibold text-gray-700 mb-3">Payment Method</p>
                <div class="grid grid-cols-3 gap-3">
                    <?php $__currentLoopData = [
                        ['id'=>'sslcommerz','label'=>'SSLCommerz','sub'=>'Card / Bank Transfer','emoji'=>'💳'],
                        ['id'=>'bkash',     'label'=>'bKash',      'sub'=>'Mobile Banking',      'emoji'=>'📱'],
                        ['id'=>'nagad',     'label'=>'Nagad',      'sub'=>'Mobile Banking',      'emoji'=>'📲'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" onclick="selectMethod('<?php echo e($pm['id']); ?>')"
                        id="method-<?php echo e($pm['id']); ?>"
                        class="method-btn p-4 rounded-xl border-2 text-left transition-all
                               <?php echo e($pm['id'] === 'sslcommerz' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'); ?>">
                        <p class="text-xl mb-1"><?php echo e($pm['emoji']); ?></p>
                        <p class="text-sm font-semibold text-gray-800"><?php echo e($pm['label']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($pm['sub']); ?></p>
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="text-center">
                <button type="submit" class="inline-flex items-center gap-2 px-10 py-3.5 bg-yellow-400 text-gray-900 font-bold rounded-xl hover:bg-yellow-300 transition-colors text-base shadow-lg">
                    ⭐ Upgrade to Premium
                </button>
                <p class="text-xs text-gray-400 mt-2">Secure payment · Cancel anytime · Instant activation</p>
            </div>
        </form>
    </div>
    <?php endif; ?>

    
    <?php if($history->isNotEmpty()): ?>
    <div class="card">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Payment History</h3>
        </div>
        <div class="divide-y divide-gray-50">
            <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="px-6 py-3 flex items-center justify-between text-sm">
                <div>
                    <p class="font-medium text-gray-900">Premium · <?php echo e($sub->duration_months); ?> month(s)</p>
                    <p class="text-xs text-gray-500"><?php echo e(ucfirst($sub->payment_method)); ?> · <?php echo e($sub->created_at->format('d M Y')); ?></p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">৳<?php echo e(number_format($sub->amount_bdt)); ?></p>
                    <span class="badge <?php echo e($sub->status_badge_color); ?>"><?php echo e(ucfirst($sub->status)); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function selectPlan(months) {
    document.getElementById('selectedMonths').value = months;
    document.querySelectorAll('.plan-btn').forEach(b => {
        b.classList.remove('border-yellow-400','ring-2','ring-yellow-300','shadow-md');
    });
    const btn = document.getElementById('plan-'+months);
    btn.classList.add('border-yellow-400','ring-2','ring-yellow-300','shadow-md');
}
function selectMethod(id) {
    document.getElementById('selectedMethod').value = id;
    document.querySelectorAll('.method-btn').forEach(b => {
        b.classList.remove('border-blue-500','bg-blue-50');
        b.classList.add('border-gray-200');
    });
    const btn = document.getElementById('method-'+id);
    btn.classList.remove('border-gray-200');
    btn.classList.add('border-blue-500','bg-blue-50');
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hp\OneDrive\Desktop\scholarify\resources\views/student/subscription/index.blade.php ENDPATH**/ ?>