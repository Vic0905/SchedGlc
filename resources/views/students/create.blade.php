<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gray-100">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div id="successMessage" class="bg-green-600 text-white p-4 mb-6 rounded-lg shadow-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const successMessage = document.getElementById('successMessage');
                            if (successMessage) {
                                setTimeout(() => {
                                    successMessage.style.transition = 'opacity 1.0s ease';
                                    successMessage.style.opacity = '0';
                                    setTimeout(() => successMessage.remove(), 500);
                                }, 3000);
                            }
                        });
                    </script>
                    
                    <!-- Form to Add Student -->
                    <div class="max-w-2xl mx-auto bg-gray-100 p-10 rounded-lg shadow-lg">
                        <form action="{{ route('students.store') }}" method="POST" class="space-y-6 ">
                            @csrf
                            
                        <!-- Back Button -->
                        <div class="flex justify-right mb-6">
                            <a href="{{ url()->previous() }}" 
                            class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-300">
                                ← Back
                            </a>
                        </div>

                            <!-- Name Input -->
                            <div>
                                <label for="name" class="block text-lg font-medium text-gray-700 mb-2">Name</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter student's name" 
                                    required>
                            </div>

                            <!-- English Name Input -->
                            <div>
                                <label for="english_name" class="block text-lg font-medium text-gray-700 mb-2">English Name</label>
                                <input 
                                    type="text" 
                                    name="english_name" 
                                    id="english_name" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter student's English name" 
                                    required>
                            </div>

                            <!-- Course Input -->
                            <div>
                                <label for="course" class="block text-lg font-medium text-gray-700 mb-2">Course</label>
                                <input 
                                    type="text" 
                                    name="course" 
                                    id="course" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter student's course" 
                                    required>
                            </div>

                            <!-- Level Input -->
                            <div>
                                <label for="level" class="block text-lg font-medium text-gray-700 mb-2">Level</label>
                                <input 
                                    type="text" 
                                    name="level" 
                                    id="level" 
                                    class="block w-full text-lg py-3 px-4 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter student's level" 
                                    required>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-center">
                                <button 
                                    type="submit" 
                                    class="bg-gray-900 hover:bg-transparent px-6 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider border-2 
                                    border-gray-500 hover:border-gray-500 text-gray-100 hover:text-gray-900 rounded-xl transition ease-in duration-300">
                                    Add Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

