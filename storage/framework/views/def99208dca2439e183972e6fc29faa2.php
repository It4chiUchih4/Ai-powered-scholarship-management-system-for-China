<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <?php $__currentLoopData = [
            ['label'=>'Universities', 'value'=>$stats['universities'],  'color'=>'text-blue-700',   'bg'=>'bg-blue-50',   'route'=>'admin.universities.index'],
            ['label'=>'Programs',     'value'=>$stats['programs'],      'color'=>'text-indigo-700', 'bg'=>'bg-indigo-50', 'route'=>'admin.programs.index'],
            ['label'=>'Scholarships', 'value'=>$stats['scholarships'],  'color'=>'text-green-700',  'bg'=>'bg-green-50',  'route'=>'admin.scholarships.index'],
            ['label'=>'Students',     'value'=>$stats['students'],      'color'=>'text-purple-700', 'bg'=>'bg-purple-50', 'route'=>'admin.dashboard'],
            ['label'=>'Applications', 'value'=>$stats['applications'],  'color'=>'text-orange-700', 'bg'=>'bg-orange-50', 'route'=>'admin.dashboard'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route($s['route'])); ?>" class="card p-5 hover:shadow-md transition-shadow">
            <p class="text-2xl font-bold <?php echo e($s['color']); ?>"><?php echo e($s['value']); ?></p>
            <p class="text-xs text-gray-500 mt-1"><?php echo e($s['label']); ?></p>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Recent Universities</h3>
                <a href="<?php echo e(route('admin.universities.create')); ?>" class="btn-primary text-xs">+ Add</a>
            </div>
            <div class="divide-y divide-gray-50">
                <?php $__currentLoopData = $recentUniversities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900"><?php echo e($uni->university_name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($uni->city); ?> · <?php echo e($uni->ranking_tier); ?></p>
                    </div>
                    <a href="<?php echo e(route('admin.universities.edit', $uni)); ?>" class="text-xs text-blue-600 hover:underline">Edit</a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Upcoming Scholarship Deadlines</h3>
                <a href="<?php echo e(route('admin.scholarships.create')); ?>" class="btn-primary text-xs">+ Add</a>
            </div>
            <div class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingDeadlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900 line-clamp-1"><?php echo e($s->scholarship_name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($s->university->university_name); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs <?php echo e($s->days_until_deadline <= 14 ? 'text-red-600 font-semibold' : 'text-gray-500'); ?>">
                            <?php echo e($s->application_deadline->format('d M Y')); ?>

                        </p>
                        <form method="POST" action="<?php echo e(route('admin.scholarships.toggle-visibility', $s)); ?>" class="mt-0.5">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-xs <?php echo e($s->is_visible ? 'text-green-600' : 'text-gray-400'); ?>">
                                <?php echo e($s->is_visible ? '👁 Visible' : '🚫 Hidden'); ?>

                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="px-6 py-6 text-center text-sm text-gray-400">No upcoming deadlines.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php $__currentLoopData = [
            ['label'=>'Add University', 'route'=>'admin.universities.create', 'icon'=>'🏛'],
            ['label'=>'Add Program',    'route'=>'admin.programs.create',    'icon'=>'📚'],
            ['label'=>'Add Scholarship','route'=>'admin.scholarships.create','icon'=>'💰'],
            ['label'=>'View All Programs','route'=>'admin.programs.index',   'icon'=>'📋'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route($link['route'])); ?>"
           class="card p-4 text-center hover:shadow-md hover:border-blue-200 transition-all">
            <p class="text-2xl mb-1"><?php echo e($link['icon']); ?></p>
            <p class="text-sm font-medium text-gray-700"><?php echo e($link['label']); ?></p>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hp\OneDrive\Desktop\scholarify\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>