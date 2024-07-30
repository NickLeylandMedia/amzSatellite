/* Library Imports */
//React
import React, { useEffect, useState } from "react";

/* Stylesheet Imports */

/* Image Imports */
import { IoArrowBack } from "react-icons/io5";

/* Component Imports */

/* Module Imports */

/* Component Interfaces */
interface Props {
  results: any[];
  termSetter: (term: string) => void;
  modeSetter: (mode: string) => void;
}

/* Component */
const Search: React.FC<Props> = ({ termSetter, results, modeSetter }) => {
  /* State Variables */

  /* End State Variables */

  /* Render Variables */
  let renderedSearch: any = <p>No Search Results!</p>;
  /* End Render Variables */

  /* Render Logic */
  if (results && results.length > 0) {
    renderedSearch = results.map((result, index) => {
      const renderedFound = result.items.map((item: any) => {
        return (
          <div className="flex flex-row justify-between my-4">
            <h3 className="text-black text-lg">SKU: {item.sku}</h3>
            <h3 className="text-black text-lg">Qty: {item.quantity}</h3>
          </div>
        );
      });
      return (
        <div
          key={result.id}
          className="searchResult border border-black p-5 m-5 relative"
        >
          {index === results.length - 1 ? (
            <h2 className="absolute top-0 right-0 p-2 bg-orange-500">
              Most Recent
            </h2>
          ) : null}
          <h3 className="">Shipment ID: {result.shipment.id}</h3>
          <h3 className="">Shipment Name: {result.shipment.name}</h3>
          <h3 className="">Shipment Date: {result.shipment.date}</h3>
          <div className="flex flex-col">{renderedFound}</div>
        </div>
      );
    });
  }
  /* End Render Logic */

  /* Functions */
  /* End Functions */

  /* Effects */

  /* End Effects */

  /* Component Return Statement */
  return (
    <>
      <IoArrowBack
        className="text-4xl text-black hover:text-red-500 mx-auto my-4 absolute top-2 left-[20%]"
        onClick={() => {
          termSetter("");
          modeSetter("manifest");
        }}
      />
      <h2 className="text-center text-3xl my-4">SKU SEARCH</h2>
      <div className="Search flex flex-row mx-auto w-[80%] items-center">
        <input
          type="text"
          name="search"
          id="search"
          className="w-[90%] border-2 border-black h-[60px] my-3 rounded-xl mx-3 pl-6"
          onChange={(e) => termSetter(e.target.value)}
        />
        <button className="bg-blue-300 px-3 h-[60px] hover:bg-blue-700">
          SEARCH
        </button>
      </div>
      <div className="searchResults grid grid-cols-3 w-[80%] mx-auto">
        {renderedSearch}
      </div>
    </>
  );
};

/* Export Statement */
export default Search;
