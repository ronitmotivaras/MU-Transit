import 'package:flutter/material.dart';

class LostAndFoundScreen extends StatefulWidget {
  const LostAndFoundScreen({super.key});

  @override
  State<LostAndFoundScreen> createState() => _LostAndFoundScreenState();
}

class _LostAndFoundScreenState extends State<LostAndFoundScreen> {
  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2, // Number of tabs
      child: Scaffold(
        appBar: AppBar(
          elevation: 0, // Removes unnecessary space
          // backgroundColor: const Color(0xFF008D62),
          bottom: const TabBar(
            tabs: [
              Tab(text: 'Lost Items'),
              Tab(text: 'Found Items'),
            ],
          ),
        ),
        body: const TabBarView(
          children: [
            LostItemsTab(),
            FoundItemsTab(),
          ],
        ),
      ),
    );
  }
}

class LostItemsTab extends StatelessWidget {
  const LostItemsTab({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: SingleChildScrollView(
        child: Center( // Centers the form on the screen
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const Text(
                "Report Lost Item",
                style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.black),
              ),
              const SizedBox(height: 16),

              // Form for Lost Item wrapped in Cards
              _buildCardField("Item Name", true),
              const SizedBox(height: 16),
              _buildCardField("Item Description", false),
              const SizedBox(height: 16),
              _buildCardField("Route No./Bus No.", true),
              const SizedBox(height: 16),
              _buildCardField("Lost Location", false),
              const SizedBox(height: 16),
              _buildCardField("Contact Information", true),
              const SizedBox(height: 16),
              _buildCardField("Date and Time of Loss", false),
              const SizedBox(height: 16),
              _buildCardField("Add Photo", false), // Added photo upload field
              const SizedBox(height: 32),

              ElevatedButton(
                onPressed: () {
                  // Handle lost item submission
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF008D62),
                  padding: const EdgeInsets.symmetric(vertical: 20.0, horizontal: 50.0), // Increased width of button
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(30.0),
                  ),
                ),
                child: const Text(
                  "Submit",
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCardField(String labelText, bool isRequired) {
    return Card(
      elevation: 5,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12.0), // More circular
      ),
      child: TextField(
        style: const TextStyle(fontWeight: FontWeight.bold), // Bold text in input
        decoration: InputDecoration(
          labelText: labelText + (isRequired ? " *" : ""), // Add * for compulsory fields
          labelStyle: const TextStyle(fontWeight: FontWeight.bold), // Bold label
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12.0),
            borderSide: const BorderSide(color: Colors.green),
          ),
        ),
      ),
    );
  }
}

class FoundItemsTab extends StatelessWidget {
  const FoundItemsTab({super.key});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: SingleChildScrollView(
        child: Center( // Centers the form on the screen
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const Text(
                "Report Found Item",
                style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.black),
              ),
              const SizedBox(height: 16),

              // Form for Found Item wrapped in Cards
              _buildCardField("Item Name", true),
              const SizedBox(height: 16),
              _buildCardField("Item Description", false),
              const SizedBox(height: 16),
              _buildCardField("Route No./Bus No.", true),
              const SizedBox(height: 16),
              _buildCardField("Found Location", false),
              const SizedBox(height: 16),
              _buildCardField("Contact Information", true),
              const SizedBox(height: 16),
              _buildCardField("Date and Time of Finding", false),
              const SizedBox(height: 16),
              _buildCardField("Add Photo", false), // Added photo upload field
              const SizedBox(height: 32),

              ElevatedButton(
                onPressed: () {
                  // Handle found item submission
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF008D62),
                  padding: const EdgeInsets.symmetric(vertical: 20.0, horizontal: 50.0), // Increased width of button
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(20.0),
                  ),
                ),
                child: const Text(
                  "Submit",
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.white),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCardField(String labelText, bool isRequired) {
    return Card(
      elevation: 5,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12.0), // More circular
      ),
      child: TextField(
        style: const TextStyle(fontWeight: FontWeight.bold), // Bold text in input
        decoration: InputDecoration(
          labelText: labelText + (isRequired ? " *" : ""), // Add * for compulsory fields
          labelStyle: const TextStyle(fontWeight: FontWeight.bold), // Bold label
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12.0),
            borderSide: const BorderSide(color: Colors.green),
          ),
        ),
      ),
    );
  }
}
