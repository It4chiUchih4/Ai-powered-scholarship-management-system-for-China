<?php $__env->startSection('title', 'Manage Universities'); ?>
<?php $__env->startSection('page-title', 'Universities'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                   class="form-input w-64" placeholder="Search universities…">
            <button class="btn-secondary">Search</button>
        </form>
        <a href="<?php echo e(route('admin.universities.create')); ?>" class="btn-primary">+ Add University</a>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">University</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">City / Province</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Tier</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-600">Language</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Programs</th>
                    <th class="px-6 py-3 text-center font-semibold text-gray-600">Active</th>
                    <th class="px-6 py-3 text-right font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $universities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-3 font-medium text-gray-900"><?php echo e($uni->university_name); ?></td>
                    <td class="px-6 py-3 text-gray-500"><?php echo e($uni->city); ?>, <?php echo e($uni->province); ?></td>
                    <td class="px-6 py-3"><span class="badge <?php echo e($uni->ranking_badge_color); ?>"><?php echo e($uni->ranking_tier); ?></span></td>
                    <td class="px-6 py-3"><span class="badge <?php echo e($uni->language_badge_color); ?>"><?php echo e($uni->language_of_instruction); ?></span></td>
                    <td class="px-6 py-3 text-center text-gray-500"><?php echo e($uni->programs_count); ?></td>
                    <td class="px-6 py-3 text-center">
                        <span class="<?php echo e($uni->is_active ? 'text-green-600' : 'text-gray-400'); ?>">
                            <?php echo e($uni->is_active ? '✓' : '—'); ?>

                        </span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo e(route('admin.universities.edit', $uni)); ?>" class="text-blue-600 hover:underline text-xs">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.universities.destroy', $uni)); ?>"
                                  onsubmit="return confirm('Delete <?php echo e(addslashes($uni->university_name)); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400">No universities found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($universities->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Hp\OneDrive\Desktop\scholarify\resources\views/admin/universities/index.blade.php ENDPATH**/ ?>