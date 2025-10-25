import { useState, useEffect } from 'react';
import './App.css';
import TaskList from '../components/TaskList';
import AddTaskForm from '../components/AddTaskForm';

function App() {

  const [tasks, setTasks] = useState([])

  const fetchTasks = async () => {
      try{
        const resposne = await fetch("http://localhost:4000/tasks.php");
        const data = await resposne.json();
        setTasks(data);
      }catch(error){
        console.error("Error fetching tasks:", error);
      }
  };

  useEffect(() => {
      fetchTasks();
  }, []);


  return (
    <div className="min-h-screen bg-gray-900 text-gray-100 flex flex-col items-center p-6">
      <div className="w-full max-w-2xl">
      <h1 className="text-3xl font-bold mb-6 text-center text-blue-400 tracking-tight">My Tasks</h1>
      <AddTaskForm onTaskAdded={fetchTasks} />
      <TaskList tasks={tasks} onChange={fetchTasks} />
      </div>
    </div>
  )
}

export default App
