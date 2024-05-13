/* Library Imports */
//React
import React from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */

/* Module Imports */

/* Component Interfaces */
interface Props {}

/* Component */
const ShipmentForm: React.FC<Props> = () => {
  /* State Variables */
  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Functions */
  /* End Functions */

  /* Effects */
  /* End Effects */

  /* Component Return Statement */
  return (
    <div className="ShipmentForm w-full flex flex-col">
      <h1 className="text-center text-4xl">Add A Shipment</h1>
      <form className="w-[50%] mx-auto">
        <div className="flex flex-col my-2">
          <label htmlFor="name">Name:</label>
          <input
            className="border border-black"
            type="text"
            id="name"
            name="name"
          />
        </div>
        <div className="flex flex-col my-2">
          <label htmlFor="date">Date:</label>
          <input
            className="border border-black"
            type="date"
            id="date"
            name="date"
          />
        </div>
        <div className="flex flex-col">
          <label htmlFor="summary">Summary</label>
          <input
            className="border border-black"
            type="textarea"
            name="summary"
            id="summary"
          />
        </div>
        <button type="submit">Add Shipment</button>
      </form>
    </div>
  );
};

/* Export Statement */
export default ShipmentForm;
