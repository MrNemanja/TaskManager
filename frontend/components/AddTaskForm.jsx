import { useState } from "react"
import "./AddTaskForm.css"

function AddTaskForm({onTaskAdded}){

    const [title, setTitle] = useState("")
    const [description, setDescription] = useState("")

    const handleInput = (e) =>
    {
        if(e.target.name == "title") setTitle(e.target.value)
        else setDescription(e.target.value)
    }

    const handleSubmit = async (e) =>
    {
        e.preventDefault()
        if (!title.trim()) return;
        const data = {title, description}

         try {
            const response = await fetch("http://localhost:4000/tasks.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ title, description }),
            });

            if (!response.ok) throw new Error("Failed to add task");

            setTitle("");
            setDescription("");
            onTaskAdded();
        } catch (error) {
            console.error(error);
        }
    };

    return(
        <form onSubmit={handleSubmit} className="bg-gray-800 p-4 rounded-2xl shadow-md mb-6 flex flex-col gap-3">
        <label>Title: </label>
        <input className="border p-2 rounded" type="text" name="title" value={title} onChange={handleInput} 
        placeholder="Task title..." required/>
        <br />
        <label>Description: </label>
        <textarea  className="bg-gray-700 text-gray-100 p-3 rounded-lg outline-none focus:ring-2 focus:ring-blue-400 transition-all" 
        name="description" value={description} onChange={handleInput} placeholder="Task description..." required>
        </textarea>
        <br />
        <br />
        <button className="bg-blue-500 hover:bg-blue-600 transition-all text-white font-semibold py-2 rounded-lg" type="submit">Add Task</button>
        </form>
    )
}

export default AddTaskForm;